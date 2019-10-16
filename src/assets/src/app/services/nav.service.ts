import { Injectable } from '@angular/core';
import { BaseService } from './base.service';
import { BehaviorSubject } from 'rxjs';

@Injectable({
                providedIn: 'root'
            })

export class NavService extends BaseService {
    static SERVICE_PREFIX = 'backoffice';
    static NAVIGATION = 'navigation';

    isShowNavBar: BehaviorSubject<boolean> = new BehaviorSubject(true);

    showNavBar() {
        this.isShowNavBar.next(true);
    }

    hideNavBar() {
        this.isShowNavBar.next(false);
    }

    getItems() {
        const path = BaseService.mapPaths([NavService.SERVICE_PREFIX, NavService.NAVIGATION]);
        return this.get(path);
    }
}
