export function moneyInputMaskAll() {
    
    document.querySelectorAll('input').forEach(element => {
        if (element.getAttribute('data-money') == "true") {                        
            Inputmask(moneyInputMask()).mask(element);
        }
    });
}
export function moneyInputMask() {
    return {
        alias: "numeric",
        groupSeparator: ",",
        autoGroup: true,
        digits: 2,
        digitsOptional: false,
        prefix: "Bs ",
        placeholder: "0"
    };
}
export function dateInputMask() {
    return {
        alias: "date",
        inputFormat: "dd/mm/yyyy",
    };
}
export function parseMoney(value) {
    if (!value) {
        return 0;
    }
    let result = value.replace(/(Bs|\s+)/ig, ``);
    result = result.replace(/,/g, ``);
    return result;
}
