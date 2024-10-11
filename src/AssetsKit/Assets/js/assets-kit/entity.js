





declareNewBridge("entity", {
    entityInputList: new Map(),

    addEntityListenersFor: function (root)
    {
        let entity = root.getAttribute("entity");
        let entityId = root.getAttribute("entity-id");

        root.querySelectorAll(`*[${entity}]`).forEach(input => {
            let ccallback = null;
            if (ccallback = this.entityInputList.get(input))
            {
                input.removeEventListener("change", ccallback);
                this.entityInputList.delete(input);
            }

            let field = input.getAttribute(entity);

            const callback = async _ =>{
                const {dateToSQL} = SharpAssetsKit.date;
                const apiUpdate = SharpAssetsKit.API.update;

                let value = input.getAttribute("selection") ?? input.value;

                if (input.type == "checkbox" || input.type == "radio")
                    value = input.checked * 1;
                else if (input.type == "time")
                    value = value === "" ? null : value.padEnd('00:00:00'.length, ':00');
                else if (input.type == "date")
                    value = value === "" ? null : dateToSQL(value);
                else if (input.type == "datetime" || input.type == "datetime-local")
                    value = dateToSQL(value, true);

                if (value === "")
                    value = null;

                try
                {
                    await (apiUpdate)(entity, {id: entityId, [field]: value})
                    document.dispatchEvent(new CustomEvent("successful-entity-update", {detail: { entity, field, value, entityId }}))
                }
                catch (error)
                {
                    document.dispatchEvent(new CustomEvent("failed-entity-update", {detail: { entity, field, value, entityId, error }}))
                }
                document.dispatchEvent(new CustomEvent(`${entity}-${entityId}-${field}`, {detail: {value: input.value}}));
            };

            this.entityInputList.set(input, callback);
            input.addEventListener("change", callback)
        });
    },

    addEntityListeners: function (target=null)
    {
        target ??= document.body;
        target.querySelectorAll("*[entity][entity-id]").forEach(addEntityListenersFor);

        if (!(target.hasAttribute("entity")))
            return;

        addEntityListenersFor(target)
    }
}, entity => {return {
    addEntityListenersFor: entity.addEntityListenersFor,
    addEntityListeners: entity.addEntityListeners,
}})

document.addEventListener("DOMContentLoaded", _ => {
    const entity = SharpAssetsKit.entity;

    let entityObserver = new MutationObserver(mutations => {
        mutations
        .map(x => x.target)
        .filter(x => 'innerHTML' in x)
        .forEach(entity.addEntityListeners);
    })
    entityObserver.observe(document.body, {childList: true, subtree: true, characterData: true});

    entity.addEntityListeners();
})