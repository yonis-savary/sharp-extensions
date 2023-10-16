/*
   _       _                           _     _
  /_\ _  _| |_ ___       __ ___ _ __  _ __| |___| |_ ___
 / _ \ || |  _/ _ \     / _/ _ \ '  \| '_ \ / -_)  _/ -_)
/_/ \_\_,_|\__\___/     \__\___/_|_|_| .__/_\___|\__\___|
                                     |_|
*/

declareNewBridge("autocomplete", {

    autocompleteBox: null,

    addAutocompleteListener: function(target, provider, onselect=null)
    {
        if (!this.autocompleteBox)
        {
            this.autocompleteBox = document.createElement("section");
            this.autocompleteBox.classList = "menu menu-option";
            this.autocompleteBox.style.overflowY = "auto";
            this.autocompleteBox.id = `autocomplete-menu`;
            document.body.appendChild(this.autocompleteBox)
        }
        let autocompleteBox = this.autocompleteBox;

        let autocomplete = async _ => {

            let targetBox = target.getBoundingClientRect();
            let maxHeight = window.innerHeight - targetBox.bottom;
            autocompleteBox.style.maxHeight = `${maxHeight}px`;

            let value = target.value;
            if (value.length < 3)
                return;

            let res = await (provider)(value);

            target.removeAttribute("selection");

            autocompleteBox.innerHTML = res.map(x => html`
                <section
                    class="autocomplete-option" value="${x[0]}"
                    ${ Object.entries(x[2] ?? {}).map(([key, value]) => `${key}=${value}`).join(" ")}
                >${x[1]}</section>
            `).join("");

            for (const option of autocompleteBox.querySelectorAll(".autocomplete-option"))
            {
                option.onclick = _ => {
                    let val = option.getAttribute("value");
                    target.setAttribute("selection", val);
                    target.value = option.innerHTML;
                    closeMenu();
                    if (onselect)
                        (onselect)(val, option.innerHTML, option);
                    target.focus();
                };
            }
            openMenu(autocompleteBox, target, "bottom")
        };

        target.addEventListener("click", autocomplete)

        let timeout = null;
        target.addEventListener("keyup", ()=>{
            closeMenu();
            if (timeout)
                clearTimeout(timeout);
            timeout = setTimeout(autocomplete, 500);
        })
    }
}, autocomplete => {return {
    addAutocompleteListener: autocomplete.addAutocompleteListener
}}, autocomplete => {
    Element.prototype.addAutocompleteListener = function(provider, onselect=null){
        autocomplete.addAutocompleteListener(this, provider, onselect);
    };
})