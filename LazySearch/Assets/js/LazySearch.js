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
            fetchQueryPossibilities: true
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
        <section class="lazySearchGrid" style="display: grid; gap: .5em; grid-template-area:
            'none', 'pagination'
            'filters', 'content';
            grid-template-columns: minmax(auto, 0px) auto;
        ">
            <section style="grid-area: 1/1/2/2" class="flex-column align-center justify-center title-section">
                <h1 class="h4 tableTitle"></h1>
            </section>
            <section style="grid-area: 2/1/3/2" class="flex-column aside-menu">
                <section class="card">
                    <section class="flex-column">
                        <section class="flex-row align-center gap-1">
                            <b class="svg-text">${svg("funnel")}${LOC.dict.filtersTitle}</b>
                            <button class="button blue icon secondary resetButton fill-left">${svg("arrow-repeat")}</button>
                            <button class="button violet icon secondary exportButton">${svg("file-earmark-arrow-down")}</button>
                        </section>
                        <section class="flex-column hide-empty gap-2 filters"></section>
                    </section>
                    <section class="hide-empty extraAside"></section>
                </section>
            </section>
            <section
                style="grid-area: 1/2/2/3; width: 100%"
                class="flex-column align-end gap-1 pagination-section"
            >
                <section class="flex-row width-100 align-center">
                    <input type="search" placeholder="${LOC.dict.searchPlaceholder}" name="${this.url}" class="search">
                    <small class="resultCount"></small>
                    <section class="fill-left flex-row gap-1 align-end pagination"></section>
                </section>
            </section>
            <section style="grid-area: 2/2/3/3" class="content-wrapper">
                <table class="content table"></table>
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
            "tableTitle"
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

        this.dom.tableTitle.innerText = options.title

        if (this.parameters.flags.fetchQueryPossibilities)
            this.meta = meta;
        else
            meta = this.meta

        if (this.parameters.flags.fetchQueryResultsCount)
            this.resultsCount = resultsCount
        else
            resultsCount = this.resultsCount;

        this.parameters.flags.fetchQueryPossibilities = false;
        this.parameters.flags.fetchQueryResultsCount = false;


        // PHP Converts {} to [], we fix this effect with this line
        if (Array.isArray(this.parameters.filters))
            this.parameters.filters = {};

        this.buildPagination(resultsCount)
        this.buildTable(data, meta, options);
        this.buildFilters(meta, options);

        this.dispatchEvent("LazySearchRefreshed");

    }


    async applyDefaults(options)
    {
        let {defaultSorts, defaultFilters} = options;

        if ((!defaultSorts) && (!defaultFilters))
            return false;

        if (typeof defaultSorts === "object" && Object.keys(defaultSorts).length)
            this.parameters.sorts = defaultSorts;

        if (typeof defaultFilters === "object" && Object.keys(defaultFilters).length)
            this.parameters.filters = defaultFilters;

        let filters = this.parameters.filters;
        Object.keys(this.parameters.filters).forEach(field => {
            if (!Array.isArray(filters[field]))
                filters[field] = [filters[field]];
        });
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

        const safeAttrVal = value => (value ?? "").toString().replaceAll("\"", "\\\"").replaceAll(">", "&gt;").replaceAll("<", "&lt;");

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

        this.root.querySelector(".resultCount").innerText =`${resultsCount} ${LOC.dict.resultsName}`

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
            meta.fields.filter(f => fieldIsNotIgnored(f.alias)).filter(x => (x.possibilities ?? []).length).map(field => `
                <details class="flex-column gap-0" ${(this.parameters.filters[field.alias] ?? []).length ? "open": ""}>
                    <summary>
                        <b>${field.alias}</b>
                    </summary>
                    <label class="flex-row align-center gap-1">
                        <input
                            type="checkbox"
                            ${field.possibilities.length === (this.parameters.filters[field.alias] ?? []).length ? "": "checked"}
                            field="${field.alias}" class="filter-all-checkbox"
                        >
                        ${LOC.dict.selectAllLabel}
                    </label>
                    <section class="padding-left-2 flex-column gap-0 scrollable max-vh-20">
                        ${field.possibilities.sort().map((x,i) => `
                        <label class="flex-row gap-1 filter-label">
                            <input
                                type="checkbox"
                                field="${field.alias}"
                                index="${i}"
                                class="filter-checkbox"
                                ${(this.parameters.filters[field.alias]??[]).includes(x) ? '': 'checked'}
                                value="${x}"
                            >
                            ${this.formatData(x)}
                        </label>
                        `).join("")}
                    </section>
                </details>
            `).join("")

        this.dom.filters.querySelectorAll(".filter-checkbox").forEach(checkbox => {
            let field = checkbox.getAttribute("field");
            let index = parseInt(checkbox.getAttribute("index"));

            let value = meta.fields.find(x => x.alias == field).possibilities[index];

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
                    this.parameters.filters[field] = meta.fields.find(x => x.alias == field).possibilities;

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

        this.parameters.flags.fetchQueryResultsCount = true;
        this.parameters.page = 0;
        this.refresh();
    }

    formatData(data)
    {
        if (data === null || typeof data == "undefined")
            return "";

        if (data.toString().match(/^\d{4}-\d{2}-\d{2}$/))
            return LOC.functions.dateTransform(data.toString());

        if (data.toString().match(/^(http|www)/))
            return html`<a href="${data}">${data}</a>`

        return html`${data}`;
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
}

document.addEventListener("DOMContentLoaded", refreshLazySearch);