import { Injectable } from '@angular/core';
import {environment} from '../../environments/environment';
export class HelperOption {
    path: string;
    constructor(_path: string) {
        this.path = _path;
    }

}
@Injectable({
  providedIn: 'root'
})
export class HelperService {
    public path = 'assets/';
    public svg_path = 'assets/svg/';
    // constructor(option: HelperOption) {
    //     this.path = option.path;
    // }
    asset(name: string) {
        const expression = /((([A-Za-z]{3,9}:(?:\/\/)?)(?:[\-;:&=\+\$,\w]+@)?[A-Za-z0-9\.\-]+|(?:www\.|[\-;:&=\+\$,\w]+@)[A-Za-z0-9\.\-]+)((?:\/[\+~%\/\.\w\-_]*)?\??(?:[\-\+=&;%@\.\w_]*)#?(?:[\.\!\/\\\w]*))?)/;
        const regex = new RegExp(expression);
        if ( name.match(regex)) {
            return name;
        } else {
            return environment.deployUrl + this.path +  name;
        }
    }
    svg(name: string) {
        return environment.deployUrl + this.svg_path + name;
    }
    media(uri: string) {
        const expression = /((([A-Za-z]{3,9}:(?:\/\/)?)(?:[\-;:&=\+\$,\w]+@)?[A-Za-z0-9\.\-]+|(?:www\.|[\-;:&=\+\$,\w]+@)[A-Za-z0-9\.\-]+)((?:\/[\+~%\/\.\w\-_]*)?\??(?:[\-\+=&;%@\.\w_]*)#?(?:[\.\!\/\\\w]*))?)/;
        const regex = new RegExp(expression);
        if ( uri.match(regex)) {
            return uri;
        } else {
            return environment.api + '/' + uri;
        }
    }
}
