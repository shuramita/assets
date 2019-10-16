import { Injectable } from '@angular/core';
import {BaseService} from '@service/base.service';

@Injectable({
  providedIn: 'root'
})
export class AuthService extends BaseService {
    static SERVICE_PREFIX = 'auth';
    static LOGOUT = 'logout';

    logout() {
        const path = BaseService.mapPaths([AuthService.SERVICE_PREFIX, AuthService.LOGOUT]);
        return this.post(path);
    }
}
