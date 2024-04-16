# Boiler - Application Boilerplates

This extension includes boilerplates that can be installed inside of your application

Their files are copied and namespace changed so you can edit them

## Availables Packages

This list may not be exhaustive, please use this command to know which packages are availables

```bash
php do install-package
```

- `Authentication`: authentication page with a login/logout controller + `IsLoggedMiddleware` that you can apply to your routes
- `Showcase`: empty templates for your showcase site with a few routes for basic pages (homepage, about, contact...)


## Install a boilerplate package

The `ìnstall-package` can be used to install one or more package inside your application

```bash

# list availables packages
php do install-package

# install a package
php do install-package <PackageName> <PackageName>...
# example
php do install-package Authentication
```

If a package was already installed and you want to reinstall it, you can use the `--overwrite` option

```bash
php do install-package Authentication --overwrite
```