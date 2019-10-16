import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { GlobalData } from '@app/models/global-data';

@Injectable({
    providedIn: 'root'
})

export class GlobalService {
    globalData: BehaviorSubject<GlobalData> = new BehaviorSubject({});

    addGlobalData(data: GlobalData) {
        this.globalData.next(data);
    }

    pushGlobalData(data: GlobalData) {
        this.globalData.next({ ...this.globalData.value, ...data });
    }

    clearGlobalData() {
        this.globalData.next({});
    }
}
