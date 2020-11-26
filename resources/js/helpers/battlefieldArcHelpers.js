

export function getXPosition(xOrigin, radius, percent, allySide) {
    let radians = getRadians(percent, allySide);
    return xOrigin + (radius * Math.cos(radians));
}

export function getYPosition(yOrigin, radius, percent, allySide) {
    let radians = getRadians(percent, allySide);
    return yOrigin - (radius * Math.sin(radians));
}

export function buildArcPath(outerRadius, innerRadius, percent, allySide = true) {
    let xOrigin = allySide ? 480 : 520;
    let yOrigin = 500;
    let outerArcEnd = {
        x: getXPosition(xOrigin, outerRadius, percent, allySide),
        y: getYPosition(yOrigin, outerRadius, percent, allySide)
    };
    let innerArcStart = {
        x: getXPosition(xOrigin, innerRadius, percent, allySide),
        y: getYPosition(yOrigin, innerRadius, percent, allySide)
    };
    return [
        "M", xOrigin, (yOrigin + innerRadius),
        "L", xOrigin, (yOrigin + outerRadius),
        "A", outerRadius, outerRadius, 0, 0, allySide ? 1 : 0, outerArcEnd.x, outerArcEnd.y,
        "L", innerArcStart.x, innerArcStart.y,
        "A", innerRadius, innerRadius, 0, 0, allySide ? 0 : 1, xOrigin, (yOrigin + innerRadius)
    ].join(" ");
}

function getRadians(percent, allySide = true) {
    let offset = (100 - percent)/100 * 180;
    let degrees = allySide ? offset + 90 : 90 - offset;
    return degrees * (Math.PI/180);
}
