import { Component, OnInit } from '@angular/core';
import {GlobalData, Customer} from '@app/models/global-data';
import {CustomerService} from '@service/customer.service';

@Component({
  selector: 'app-customer',
  templateUrl: './customer.component.html',
  styleUrls: ['./customer.component.scss']
})
export class CustomerComponent implements OnInit {

    globalData: GlobalData;
    data: Array<Customer>;
    displayedColumns = [
        'id',
        'name',
        'email',
        'more'
    ];
    constructor(
        private customerService: CustomerService,
    ) { }

    ngOnInit() {
        this.customerService.myCustomers().subscribe(res => {
            this.data = res.data;
        });
    }

}
