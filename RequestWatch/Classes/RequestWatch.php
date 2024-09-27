<?php

namespace SharpExtensions\RequestWatch\Classes;

use InvalidArgumentException;
use Sharp\Classes\Core\Component;
use Sharp\Classes\Core\Configurable;
use Sharp\Classes\Core\Logger;
use Sharp\Classes\Data\Database;
use Sharp\Classes\Data\DatabaseQuery;
use Sharp\Classes\Data\ObjectArray;
use Sharp\Classes\Env\Session;
use Sharp\Classes\Env\Storage;
use Sharp\Classes\Http\Request;
use Sharp\Classes\Web\Route;
use Sharp\Core\Utils;
use Throwable;

class RequestWatch
{
    use Component, Configurable;

    const DB_NAME = "requestWatch.db";
    const LAST_ROW_ID_KEY = "requestWatch-lastId";

    protected static ?Database $database = null;
    protected static ?Logger $logger = null;

    protected ?int $rowId = null;
    protected array $config;
    protected ?int $lastRowId = null;

    protected static function initializeGlobals()
    {
        $storage = Storage::getInstance();

        self::$logger = new Logger("request-watch.csv");

        $exists = $storage->isFile(self::DB_NAME);
        self::$database = new Database("sqlite", self::DB_NAME);

        if ($exists)
            return;

        try
        {
            self::$logger->info("Initializing database schema");
            $schemaScript = file_get_contents(Utils::relativePath("SharpExtensions/RequestWatch/schema.sql"));

            ObjectArray::fromExplode(";", $schemaScript)
            ->filter() // Filter empty lines
            ->forEach(fn($query) => self::$database->query($query));
        }
        catch(Throwable $err)
        {
            self::$database = null;
            self::$logger->error("Got error, cannot create RequestWatch database", $err);
            $storage->unlink(self::DB_NAME);
            return;
        }
        self::$logger->info("Database successfuly initialized");

    }

    public function __construct()
    {
        if (!self::$database)
            self::initializeGlobals();

        $this->configuration = $this->readConfiguration();

        $session = Session::getInstance();
        if ($lastRowId = $session->try(self::LAST_ROW_ID_KEY))
            $this->lastRowId = $lastRowId;
    }

    public static function getDefaultConfiguration(): array
    {
        return [
            "reduce-slugs" => true,
            "duration-ignore-api-requests" => true
        ];
    }


    public function registerRequest(Request $request)
    {
        $db = self::$database;
        $ip = $_SERVER["REMOTE_ADDR"] ?? "0.0.0.0";
        $path = $request->getPath();
        $method = $request->getMethod();

        $db->query(
            "INSERT INTO user_request (ip, method, path) VALUES {}",
            [ [$ip, $method, $path] ]
        );

        $this->rowId = $db->lastInsertId();

        if ($lastRowId = $this->lastRowId)
        {
            try
            {
                $db->query("UPDATE user_request SET previous = {} WHERE id = {}", [$lastRowId, $this->rowId]);
            }
            catch (Throwable $err)
            {
                self::$logger->error("Could not update last row [$lastRowId]", $err, "Skipping row");
                Session::getInstance()->set(self::LAST_ROW_ID_KEY, null);
                $this->lastRowId = null;
            }
        }

        $isAPI = str_contains($path, "/api") || ($method != "GET");
        $ignoreApi = $this->configuration["duration-ignore-api-requests"] ;

        if (!($isAPI && $ignoreApi))
            Session::getInstance()->set(self::LAST_ROW_ID_KEY, $this->rowId);
    }

    public function registerRoute(Route $route)
    {
        $path = $route->getPath();

        if (!$this->rowId)
            return self::$logger->warning("Cannot register route for path [$path]");

        if ($this->configuration["reduce-slugs"])
            $path = preg_replace("/\{.+?\}/", "{}", $path);

        $db = self::$database;
        $db->query("UPDATE user_request SET route = {} WHERE id = {}", [$path, $this->rowId]);
    }



    /**
     * Get most viewed ROUTE (not request path) for a period of time
     * @param string $from From date (yyyy-mm-dd)
     * @param string $to To date (yyyy-mm-dd)
     * @param int $limit Rows to fetch (set null for unlimited)
     */
    public static function mostViewedRouteForPeriod(string $from, string $to, ?int $limit=10)
    {
        $dateFormat = "/^\d{4}-\d{2}-\d{2}$/";

        if ((!preg_match($dateFormat, $from)) || (!preg_match($dateFormat, $to)))
            throw new InvalidArgumentException("Both [from] and [to] argument must be a date (yyyy-mm-dd)");

        $request = new DatabaseQuery("user_table", DatabaseQuery::SELECT);
        $request->whereSQL("timestamp BETWEEN {} AND {}", [$from, $to]);

        if ($limit)
            $request->limit($limit);

        return $request->fetch(self::$database);
    }




    protected static function buildUserTree(int $parent, bool $skipApi=true)
    {
        $db = self::$database;

        $skipApiCondition = "";
        if ($skipApi)
            $skipApiCondition = "AND (path NOT LIKE '%api%' AND method = 'GET')";

        $leafs = $db->query("SELECT * FROM user_request WHERE previous = {} $skipApiCondition", [$parent]);

        foreach ($leafs as &$leaf)
        {
            $leaf = [
                "data" => $leaf,
                "childs" =>  self::buildUserTree($leaf["id"], $skipApi)
            ];
        }

        return $leafs;
    }


    public static function getUserNavigationTree(int $rowId)
    {
        return self::buildUserTree($rowId);
    }
}