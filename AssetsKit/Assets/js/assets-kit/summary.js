declareNewBridge("summary", {

    Summary: class {
        element = null;
        index = [];

        refreshTimeout = null;

        doCollapse = false;

        constructor(element)
        {
            this.element = element

            setTimeout(_ => this.buildSummary(), 500);

            document.addEventListener("scroll", event => {
                if (this.refreshTimeout)
                    clearTimeout(this.refreshTimeout);

                this.refreshTimeout = setTimeout(_ => this.refreshSummaryPosition(event), 50);
            });

            this.doCollapse = this.element.hasAttribute("do-collapse");
        }

        #getNodeLevel(element)
        {
            return parseInt(element.nodeName.substr(1));
        }

        #getTitleSection(element, index)
        {
            let level = this.#getNodeLevel(element) // h1 => 1, h2 => 2 ...etc
            return `
                <span class="summary-element" level="${level}" index="${index}">
                    ${element.getAttribute("title") ?? element.innerText}
                </span>
            `
        }

        async buildSummary()
        {
            let titles = Array.from(document.querySelectorAll("h1,h2,h3"));

            let {fadeIn, fadeOut} = SharpAssetsKit.animation;

            await fadeOut(this.element);
            this.element.innerHTML = titles.map((x,i) => this.#getTitleSection(x,i)).join("")
            await fadeIn(this.element);

            this.element.querySelectorAll(".summary-element").forEach(x => {
                x.addEventListener("click", event => this.gotoTitle(x))
            })

            this.index = []
            titles.forEach((element, index) => {
                let offsetY = element.getBoundingClientRect().top + window.scrollY
                this.index.push({
                    summaryElement: this.element.querySelector(`[index='${index}']`),
                    element,
                    position: offsetY,
                    level: this.#getNodeLevel(element)
                })
            });

            this.refreshSummaryPosition()
        }

        gotoTitle(link)
        {
            let index = link.getAttribute("index");
            let title = this.index[index].element;

            title.scrollIntoView({ behavior: "smooth", block: "center", inline: "center" })
            this.refreshSummaryPosition();
        }

        refreshSummaryPosition()
        {
            let current = window.scrollY + (window.innerHeight / 2);

            let lastActive;
            if (lastActive = this.element.querySelector(".active"))
                lastActive.classList.remove("active");

            let newActiveIndex = null;

            for (let index=0; index<this.index.length; index++)
            {
                let {element, position} = this.index[index];

                if (current >= position)
                    newActiveIndex = index;

                if (current < position)
                    break;
            }

            if (newActiveIndex)
            {
                let title = this.element.querySelector(`[index='${newActiveIndex}']`);
                title.classList.add("active");

                if (this.doCollapse)
                    this.collapseUnopened(newActiveIndex);
            }
        }

        collapseUnopened(index)
        {
            const COLLAPSE_LEVEL = this.element.getAttribute("collapse-level") ?? 2; // h2

            let initialLevel = this.index[index].level;
            let summaryElement = this.index[index].summaryElement;
            summaryElement.style.display = "";

            let previousSiblings = this.index.slice(0, index).reverse();
            let nextSiblings = this.index.slice(index+1);

            let minLevel = initialLevel
            for (let sibling of previousSiblings)
            {
                if (sibling.level <= minLevel)
                {
                    minLevel = sibling.level
                    sibling.summaryElement.style.display = "";
                }
                else
                {
                    sibling.summaryElement.style.display = "none";
                }
            }


            let breaker = null;
            for (let i=0; i<nextSiblings.length; i++)
            {
                if (nextSiblings[i].level >= initialLevel && nextSiblings[i].level != COLLAPSE_LEVEL)
                {
                    nextSiblings[i].summaryElement.style.display = "";
                }
                else
                {
                    breaker = i;
                    break;
                }
            }

            if (breaker)
            {
                nextSiblings.slice(breaker)
                .filter(x => x.level > COLLAPSE_LEVEL)
                .forEach(x => x.summaryElement.style.display = "none");
            }
        }
    },


    makeSummary : function(element){
        return new (this.Summary)(element);
    }

}, summary => {return {
    makeSummary: summary.makeSummary
}})