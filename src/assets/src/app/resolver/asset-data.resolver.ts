import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, Resolve, RouterStateSnapshot } from '@angular/router';
import { GlobalService } from '../services/global.service';
import { AssetService } from '../services/asset.service';
import { Observable } from 'rxjs';

@Injectable()
export class AssetDataResolver implements Resolve<any> {
    constructor(private assetService: AssetService,
        private globalService: GlobalService) {
    }

    resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<any> {

        return Observable.create(obs => {
            this.assetService.getSystemInfo().subscribe(res => {
                this.globalService.addGlobalData(res);
                obs.next({
                    globalData: res
                });
                obs.complete();
            });
        });
    }
}
