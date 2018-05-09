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
export function parseMoney(value) {
    if (!value) {
        return 0;
    }
    let result = value.replace(/(Bs|\s+)/ig, ``);
    result = result.replace(/,/g, ``);
    return result;
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
export function getCurrentDate() {
    axios.get("/get_current_date")
    .then(response =>{
        console.log(response);
    }).catch(error =>{
        console.log(error);
    })
    ;
}