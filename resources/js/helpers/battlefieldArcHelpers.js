const Y_ORIGIN = 500;

export function getXPosition(radius, percent, allySide) {
    let xOrigin = getXOrigin(allySide);
    let radians = getRadians(percent, allySide);
    return xOrigin + (radius * Math.cos(radians));
}

export function getYPosition(radius, percent, allySide) {
    let radians = getRadians(percent, allySide);
    return 500 - (radius * Math.sin(radians));
}

export function buildArcPath(combatPositionName, percent, allySide) {

    let xOrigin = getXOrigin(allySide);
    let innerRadius = getInnerRadius(combatPositionName);
    let outerRadius = getOuterRadius(combatPositionName);

    let outerArcEnd = {
        x: getXPosition(outerRadius, percent, allySide),
        y: getYPosition(outerRadius, percent, allySide)
    };
    let innerArcStart = {
        x: getXPosition(innerRadius, percent, allySide),
        y: getYPosition(innerRadius, percent, allySide)
    };

    return [
        "M", xOrigin, (Y_ORIGIN + innerRadius),
        "L", xOrigin, (Y_ORIGIN + outerRadius),
        "A", outerRadius, outerRadius, 0, 0, allySide ? 1 : 0, outerArcEnd.x, outerArcEnd.y,
        "L", innerArcStart.x, innerArcStart.y,
        "A", innerRadius, innerRadius, 0, 0, allySide ? 0 : 1, xOrigin, (Y_ORIGIN + innerRadius)
    ].join(" ");
}

function getXOrigin(allySide) {
    return allySide ? 480: 520;
}

function getRadians(percent, allySide) {
    let offset = (100 - percent)/100 * 180;
    let degrees = allySide ? offset + 90 : 90 - offset;
    return degrees * (Math.PI/180);
}

export function getInnerRadius(combatPositionName) {
    switch (combatPositionName) {
        case 'front-line':
            return 0;
        case 'back-line':
            return 220;
        case 'high-ground':
        default:
            return 350;
    }
}

export function getOuterRadius(combatPositionName) {
    switch (combatPositionName) {
        case 'front-line':
            return 220;
        case 'back-line':
            return 350;
        case 'high-ground':
        default:
            return 450;
    }
}

export function getColor({combatPositionName, allySide}) {
    switch (combatPositionName) {
        case 'front-line':
            return allySide ? '#298acf' : '#e85c35';
        case 'back-line':
            return allySide ? '#29b1cf' : '#fc7e23';
        case 'high-ground':
        default:
            return allySide ? '#29cfc1' : '#ffa500';
    }
}
