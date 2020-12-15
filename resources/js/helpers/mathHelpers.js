
export function shortenedNotation(number) {

    let suffix = '';
    let denominator = 1;
    let log = Math.log10(number);
    let decimals = 0;
    switch (true) {
        case log >= 9:
            suffix = 'b';
            denominator = 1000000000;
            decimals = 11 - Math.floor(log);
            break;
        case log >= 6:
            suffix = 'm';
            denominator = 1000000;
            decimals = 8 - Math.floor(log);
            break;
        case log >= 3:
            suffix = 'k';
            denominator = 1000;
            decimals = 5 - Math.floor(log);
            break;
    }

    let total = number/denominator;
    return total.toFixed(decimals) + suffix;
}
