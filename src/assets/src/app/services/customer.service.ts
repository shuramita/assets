import { Injectable } from '@angular/core';
import {BaseService} from '@service/base.service';

@Injectable({
  providedIn: 'root'
})
export class CustomerService extends BaseService {
    static SERVICE_PREFIX = 'asset';
    static CUSTOMER = 'customers';

    myCustomers() {
        const path = BaseService.mapPaths([CustomerService.SERVICE_PREFIX, CustomerService.CUSTOMER]);
        return this.post(path);
    }
}
