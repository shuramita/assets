export function stringCompare(strA: string, strB: string) {
    const isEmptyStr = str => {
        return !str || 0 === str.length;
    };

    if (isEmptyStr(strA) || isEmptyStr(strB)) {
        return false;
    }

    return strA.toLowerCase().trim() === strB.toLowerCase().trim();
}