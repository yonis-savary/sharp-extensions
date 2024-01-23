if (!('addMenuListeners' in window))
    throw new Error("[assets-kit/menu.js] is needed")

if (!('svg' in window))
    throw new Error("[assets-kit/svg.js] is needed")


const LOCALES = {
    "en": {
        dict: {
            selectAllLabel     : "All",
            filtersTitle       : "Filters",
            resetFilters       : "Reset filters",
            exportLabel        : "Export",
            searchPlaceholder  : "Search...",
            resultsName        : "results"
        },
        functions: {
            dateTransform: e => e
        }
    },
    "fr": {
        dict: {
            selectAllLabel     : "Tous",
            filtersTitle       : "Filtres",
            resetFilters       : "Réinitialiser",
            exportLabel        : "Exporter",
            searchPlaceholder  : "Rechercher...",
            resultsName        : "resultats"
        },
        functions: {
            dateTransform: e => e.split("-").reverse().join("/")
        }
    }
}

const LOC = LOCALES[
    (typeof LAZYSEARCH_CONFIGURATION !== "undefined" ?
        LAZYSEARCH_CONFIGURATION.locale :
        null ) ?? 'en'
];

/**
 * Events
 *
 * LazySearchInitialized
 * LazySearchRefreshed
 *
 * LazySearchLoaded
 *
 */

class LazySearch
{
    url = null;
    root = null;
    dom = {};

    meta = null;
    resultsCount = null;
    data = null;

    renderCallbacks = [];

    response = null;

    flags = {
        builtBody: false
    };

    parameters = {
        flags: {
            fetchQueryResultsCount: true,
            fetchQueryPossibilities: true,
            canUseDefaults: true
        },
        mode: "data",
        page: 0,
        size: 50,
        search: "",
        filters: {},
        sorts: [],
        extras: {}
    }

    renderFunctions = []


    constructor(root)
    {
        this.id = root.id || `lazySearch_${((new Date).getTime()).toString(16)}_${ Math.floor((Math.random()*100000)).toString().padStart(5, "0") }`;
        this.root = root;
        this.url = root.getAttribute("url")

        this.root.innerHTML = `
        <h1 class="h4 tableTitle"></h1>
        <section class="lazySearchGrid flex-column">
            <section class="flex-column align-end gap-1 pagination-section">
                <section class="flex-row width-100 align-center">
                    <input type="search" placeholder="${LOC.dict.searchPlaceholder}" name="${this.url}" class="search">
                    <section class="flex-row align-center gap-1">
                        <button class="button blue icon secondary" bottom menu="${this.id}-filterMenu" title="${LOC.dict.filtersTitle}">${svg("funnel")}</button>
                        <button class="button blue icon secondary resetButton fill-left">${svg("arrow-repeat")}</button>
                        <button class="button violet icon secondary exportButton">${svg("file-earmark-arrow-down")}</button>
                    </section>
                    <section class="fill-left flex-row gap-1 align-end pagination"></section>
                </section>
            </section>
            <section style="grid-area: 2/2/3/3" class="content-wrapper">
                <table class="content table striped width-100"></table>
            </section>
        </section>
        <section class="menu filterMenu" id="${this.id}-filterMenu" >
            <section class="flex-column">
                <b>${LOC.dict.filtersTitle} (<span class="filtersNumber"></span>)</b>
                <hr>
                <section class="flex-row scrollable horizontal filters"></section>
                <hr>
                <section class="hide-empty extraAside"></section>
            </section>
        </section>
        `;

        [
            "filters",
            "pagination",
            "search",
            "content",
            "resetButton",
            "exportButton",
            "extraAside",
            "tableTitle",
            "filtersNumber"
        ].forEach(
            classname => this.dom[classname] = this.root.querySelector("."+classname)
        );

        this.dom.search.addEventListener("change", ()=>{
            this.parameters.search = this.dom.search.value || null;
            this.parameters.page = 0;
            this.parameters.flags.fetchQueryResultsCount = true;
            this.refresh()
        })

        this.dom.resetButton?.addEventListener("click", ()=>this.reset());
        this.dom.exportButton?.addEventListener("click", ()=>this.export());

        this.dispatchEvent("LazySearchInitialized");
    }

    addRenderFunction(...callbacks)
    {
        this.renderCallbacks.push(...callbacks)
        this.refresh()
    }

    async export()
    {
        let parametersMock = Object.assign({}, this.parameters);
        parametersMock.mode = "file";

        // TODO : Find a way to download with a POST query
        window.open(`${this.url}?parameters=${JSON.stringify(parametersMock)}`)
    }

    async reset()
    {
        this.parameters.page = 0;
        this.parameters.search = "";
        this.parameters.filters =  {};
        this.parameters.sorts =  [];
        this.parameters.flags.fetchQueryPossibilities = true;
        this.parameters.flags.fetchQueryResultsCount = true;
        this.parameters.flags.canUseDefaults = true;
        this.refresh();
    }

    async dispatchEvent(eventName)
    {
        this.root.dispatchEvent(new CustomEvent(eventName, {detail: {
            instance: this
        }}));
    }

    async refresh(forceReload=false)
    {
        if (!this.url)
        {
            let newURL;
            if ((newURL = this.root.getAttribute("url")))
                return;
        }

        if (forceReload)
        {
            this.parameters.flags.fetchQueryPossibilities = true;
            this.parameters.flags.fetchQueryResultsCount = true;
        }

        this.dom.content.animateAsync([{opacity: 1},{opacity: .5}], {duration: 100});
        this.dom.content.style.opacity = .5;

        let body = await fetch(this.url , {
            body: JSON.stringify(this.parameters),
            method: "POST",
            headers: {'Content-Type': 'application/json'}
        });
        body = this.response = await body.json();

        this.dom.content.animateAsync([{opacity: .5}, {opacity: 1}], {duration: 100});
        this.dom.content.style.opacity = 1;

        let {meta, data, options, queryParameters, resultsCount} = body;

        this.parameters = queryParameters;

        if (Array.isArray(this.parameters.extras))
            this.parameters.extras = {};

        if (this.parameters.flags.fetchQueryPossibilities)
            this.meta = meta;
        else
            meta = this.meta

        if (this.parameters.flags.fetchQueryResultsCount)
            this.resultsCount = resultsCount
        else
            resultsCount = this.resultsCount;

        this.dom.tableTitle.innerText = options.title + ` (${this.resultsCount})`

        this.parameters.flags.fetchQueryPossibilities = false;
        this.parameters.flags.fetchQueryResultsCount = false;
        this.parameters.flags.canUseDefaults = false;

        // PHP Converts {} to [], we fix this effect with this line
        if (Array.isArray(this.parameters.filters))
            this.parameters.filters = {};

        this.buildPagination(resultsCount)
        this.buildTable(data, meta, options);
        this.buildFilters(meta, options);

        this.dispatchEvent("LazySearchRefreshed");

        this.dom.filtersNumber.innerText =
            Object.keys(this.parameters.filters).length +
            this.parameters.sorts.length;
    }

    async buildTable(data, meta, options)
    {
        let isIgnored = field => options.fieldsToIgnore.includes(field)
        let displayable = meta.fields.map(x => x.alias).filter(x => !isIgnored(x));

        let links = {};
        options.lazySearchLinks.forEach(link => {
            links[link.fieldLink] = row => `${link.prefix}${row[link.fieldValue]}${link.suffix}`
        });
        let isLink = field => Object.keys(links).includes(field);

        const safeAttrVal = value => (value ?? "").toString().replaceAll("\"", "&quot;").replaceAll(">", "&gt;").replaceAll("<", "&lt;");

        this.dom.content.innerHTML = `
        <thead>
            ${displayable.map(field => `
            <th>
                <section
                    class="flex-row gap-2 sort-button align-center ${this.parameters.sorts[0] == field ? "fg-blue": ""}"
                >
                    ${this.parameters.sorts[0] !== field ?
                        `<span class="svg-text sort-asc" field="${field}">${svg('filter', 18)}</span>`:
                        this.parameters.sorts[1] === "ASC" ?
                            `<span class="svg-text sort-desc" field="${field}">${svg('sort-alpha-down', 18)}</span>`:
                            `<span class="svg-text sort-none" field="${field}">${svg('sort-alpha-down-alt', 18)}</span>`

                    }
                    <b>${field}<b>
                </section>
            </th>
            `).join("")}
        </thead>
        <tbody>
            ${data.map(row => `
                <tr ${meta.fields.map(field => `${field.alias}="${safeAttrVal(row[field.alias])}"`).join(" ")}>
                ${meta.fields.map(field =>
                    isIgnored(field.alias) ?
                        ``:
                        isLink(field.alias) ?
                            `<td ${field.alias}="${safeAttrVal(row[field.alias])}" title="${safeAttrVal(row[field.alias])}"><a href="${links[field.alias](row)}">${this.formatData(row[field.alias])}</a></td>`:
                            `<td ${field.alias}="${safeAttrVal(row[field.alias])}" title="${safeAttrVal(row[field.alias])}">${this.formatData(row[field.alias])}</td>
                `).join("")}
                ${(this.renderCallbacks ?? []).map(callback => callback(row, meta.fields)).join("")}
                </tr>
                `
            ).join("")}
        </tbody>
        `

        const getSortCallback = (input, mode) => {
            return event => {
                if (!mode)
                    this.parameters.sorts = [];
                else
                    this.parameters.sorts = [input.getAttribute("field"), mode];

                this.parameters.page = 0;
                this.refresh();
            }
        }

        this.dom.content.querySelectorAll(".sort-asc").forEach(button => {
            button.parentNode.addEventListener("click", getSortCallback(button, "ASC"))
        })
        this.dom.content.querySelectorAll(".sort-desc").forEach(button => {
            button.parentNode.addEventListener("click", getSortCallback(button, "DESC"))
        })
        this.dom.content.querySelectorAll(".sort-none").forEach(button => {
            button.parentNode.addEventListener("click", getSortCallback(button, null))
        })

    }

    async buildPagination(resultsCount)
    {
        let maxPage = Math.ceil(resultsCount / this.parameters.size);
        let range = Array(5).fill(0).map((_,i) => this.parameters.page + i - 1 ).filter(x => 0 < x && x <= maxPage);
        let minRange = Math.min(...range);
        let maxRange = Math.max(...range);

        if (maxPage == 0)
            return this.dom.pagination.innerHTML = "";

        const pageButton = page => `<button page="${page}" class="button icon secondary ${page === this.parameters.page+1 ? "active" :''} ">${page}</button>`

        this.dom.pagination.innerHTML = `
            ${minRange <= 1 ? "":`${pageButton(1)}...`}
            ${range.map(pageButton).join("")}
            ${maxRange >= maxPage ? "": `...${pageButton(maxPage)}`}
        `

        this.dom.pagination.querySelectorAll("button[page]").forEach(button => {
            button.addEventListener("click", _ =>{
                this.parameters.page = parseInt(button.getAttribute("page"))-1;
                this.refresh();
            })
        })
    }

    async buildFilters(meta, options)
    {
        let fieldIsNotIgnored = f => (!(options.fieldsToIgnore.includes(f)));

        for (let [key, value] of Object.entries(this.parameters.filters))
        {
            if (value instanceof Object && (!Array.isArray(value)))
                value = [];
            else if (!Array.isArray(value))
                value = [value]

            this.parameters.filters[key] = value ;
        }

        this.dom.filters.innerHTML =
            meta.fields
                .filter(f => fieldIsNotIgnored(f.alias))
                .filter(x => (x.possibilities ?? []).concat(this.parameters.filters[x.alias] ?? []).length)
                .map(field => `
                <section class="flex-column gap-2" ${(this.parameters.filters[field.alias] ?? []).length ? "open": ""}>
                    <section class="flex-column gap-1">
                        <b>${field.alias}</b>
                        <hr>
                    </section>
                    <section class="flex-column gap-0 filter-section">
                        <label class="flex-row align-center gap-1">
                            <input
                                type="checkbox"
                                field="${field.alias}" class="filter-all-checkbox"
                            >
                            ${LOC.dict.selectAllLabel}
                        </label>
                        <section class="padding-left-2 flex-column gap-0 scrollable max-vh-40">
                            <section>
                                ${field.possibilities
                                    .concat(this.parameters.filters[field.alias] ?? [])
                                    .uniques()
                                    .sort()
                                    .map((x,i) => `
                                <label class="flex-row gap-1 filter-label">
                                    <input
                                        type="checkbox"
                                        field="${field.alias}"
                                        class="filter-checkbox"
                                        ${(this.parameters.filters[field.alias]??[]).includes(x) ? '': 'checked'}
                                        value="${x}"
                                    >
                                    <span class="one-liner" style="max-width: 30ch">
                                        ${this.formatData(x, false)}
                                    </span>
                                </label>
                                `).join("")}
                            </section>
                        </section>
                    </section>
                </section>
            `).join("")

        this.dom.filters.querySelectorAll(".filter-section").forEach(section => {
            let allCheckboxAreChecked = true;

            let checkboxes = section.querySelectorAll(".filter-checkbox");

            for (let checkbox of checkboxes)
            {
                if (checkbox.checked)
                    continue;

                allCheckboxAreChecked = false;
                break;
            }

            section.querySelector(".filter-all-checkbox").checked = allCheckboxAreChecked

        })

        this.dom.filters.querySelectorAll(".filter-checkbox").forEach(checkbox => {
            let value = checkbox.getAttribute("value");
            checkbox.addEventListener("change", event => this.updateFilter(event, value));
        });

        this.dom.filters.querySelectorAll(".filter-all-checkbox").forEach(checkbox => {
            checkbox.addEventListener("change", ()=>{
                let field = checkbox.getAttribute("field");
                this.dom.filters.querySelectorAll(`.filter-checkbox[field='${field}']`).forEach(target => {
                    target.checked = checkbox.checked;
                });

                if (checkbox.checked)
                    this.parameters.filters[field] = [];
                else
                    this.parameters.filters[field] = meta.fields
                        .find(x => x.alias == field).possibilities
                        .concat(this.parameters.filters[field] ?? []);

                this.parameters.flags.fetchQueryResultsCount = true;
                this.parameters.flags.fetchQueryPossibilities = true;

                this.refresh()
            });
        })
    }

    updateFilter(event, value)
    {
        let field = event.target.getAttribute("field");

        this.parameters.filters[field] ??= [];
        if (event.target.checked)
            this.parameters.filters[field] = this.parameters.filters[field].filter(x => x != value);
        else
            this.parameters.filters[field].push(value);

        this.parameters.flags.fetchQueryPossibilities = true;
        this.parameters.flags.fetchQueryResultsCount = true;
        this.parameters.page = 0;
        this.refresh();
    }

    formatData(data, supportNewLines=true)
    {
        if (data === null || typeof data == "undefined")
            return "";

        if (data.toString().match(/^\d{4}-\d{2}-\d{2}$/))
            return LOC.functions.dateTransform(data.toString());

        if (data.toString().match(/^(http|www)/))
            return html`<a href="${data}">${data}</a>`


        let toReturn = html`${data.toString()}`;

        return supportNewLines ? toReturn.replaceAll("\n", "<br>") : toReturn;
    }


    setExtra(extras) { this.parameters.extras = extras ; this.refresh(); }
    getExtra() { return this.parameters.extras }
    editExtra(callback) { this.setExtra(callback(this.getExtra())); }
}

window.lazySearchInstances = {}

async function refreshLazySearch()
{
    let promises = []

    let lazySearchTables = document.querySelectorAll(".lazySearch");
    lazySearchTables.forEach(table => {
        promises.push( new Promise(res => table.addEventListener("LazySearchInitialized", res) ) );
        let instance = new LazySearch(table);
        window.lazySearchInstances[instance.id] = instance;
    });

    await Promise.allSettled(promises)
    document.dispatchEvent(new Event("LazySearchLoaded"));

    Object.values(lazySearchInstances).forEach(element => {
        element.refresh(true);
    });

    addMenuListeners()
}

document.addEventListener("DOMContentLoaded", refreshLazySearch);