import Axios from "axios";

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
export function dateInputMaskAll() {
    document.querySelectorAll('input').forEach(element => {
        if (element.getAttribute('data-date') == "true") {
            Inputmask(dateInputMask()).mask(element);
        }
    });
}
export function dateMonthYearInputMask() {
    return {
        alias: "mm/yyyy"
    };
}
export function monthYearInputMaskAll() {
    document.querySelectorAll('input').forEach(element => {
        if (element.getAttribute('data-month-year') == "true") {
            Inputmask(dateMonthYearInputMask()).mask(element);
        }
    });
}
export function parseMoney(value) {
    if (!value) {
        return 0;
    }
    if (typeof value === 'string'){
        let result = value.replace(/(Bs|\s+)/ig, ``);
        result = result.replace(/,/g, ``);
        return result;
    }
    return (typeof value === 'number') ? value : alert('error: parseMoney');
}

export function cellPhoneInputMaskAll() {
    document.querySelectorAll('input').forEach(element => {
        if (element.getAttribute('data-cell-phone') == "true") {
            Inputmask(cellPhoneInputMask()).mask(element);
        }
    });
}
export function cellPhoneInputMask() {
    return "(999)-99999";
}
export function phoneInputMaskAll() {
    document.querySelectorAll('input').forEach(element => {
        if (element.getAttribute('data-phone') == "true") {
            Inputmask(phoneInputMask()).mask(element);
        }
    });
}
export function phoneInputMask() {
    return "(9) 999-999";
}
export function getGender(value) {
    var gender = '';
    if (!value) {
        return gender;
    }
    switch (value.toUpperCase()) {
        case "M":
            gender = 'Masculino';
        break;
        case "F":
            gender = 'Femenino';
        break;
    }
    return gender;
}

export function flashErrors(prefix, errors, level) {
    console.log(errors);
    for (const key in errors) {
        let value = errors[key];

        if (errors.hasOwnProperty(key)) {
            flash(`${prefix}: ${value}`, level || "error");
        } else {
        }
    }
}
export function camelCaseToSnakeCase(myStr) {
    if (myStr) {
        return myStr.replace(/([a-z])([A-Z])/g, '$1_$2').toLowerCase();
    }
    return null;
}
export function isPensionEntitySenasir(value){
    return value == 5
}
export function getNamePensionEntity(value){
    if (value == 5){
        return 'SENASIR'
    }
    return 'APS'
}
export function canOperation(operation, permissions){
    let found = permissions.find(v => v.operation == operation);
    if(found){
        return found.value
    }
    return false
}