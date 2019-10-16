export class NavItem {
    public link: string;
    public name: string;
    public icon: object;

    constructor(link: string, name: string, icon: object) {
        this.link = link;
        this.name = name;
        this.icon = icon;
    }

    public validatedURl() {
        return this.link;
    }
}
