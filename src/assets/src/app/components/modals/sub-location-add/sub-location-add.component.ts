import { Component, EventEmitter, OnDestroy, OnInit, Output } from '@angular/core';
import { FormArray, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { NavService } from '@service/nav.service';
import { GlobalService } from '@service/global.service';
import { MatDialogRef } from '@angular/material';
import { GlobalData, Price } from '@app/models/global-data';

@Component({
    selector: 'app-sub-location-add',
    templateUrl: './sub-location-add.component.html',
    styleUrls: ['./sub-location-add.component.scss']
})
export class SubLocationAddComponent implements OnInit, OnDestroy {
    @Output() addedSubLocation: EventEmitter<any> = new EventEmitter();
    globalData: GlobalData;
    subLocationForm: FormGroup;

    sizeUnits = ['sqft', 'sqmt', 'sqin', 'sqyd'];
    sizeUnit = 'sqft';

    normalPrices: FormArray;
    peakPrices: FormArray;
    offPeakPrices: FormArray;

    normalPriceList: Array<Price> = [];
    peakPriceList: Array<Price> = [];
    offPeakPriceList: Array<Price> = [];

    constructor(private _dialogRef: MatDialogRef<SubLocationAddComponent>,
        private navService: NavService,
        private globalService: GlobalService,
        private _formBuilder: FormBuilder) {

        this.subLocationForm = this._formBuilder.group({
            name: [null, Validators.required],
            size: [null, Validators.required],
            description: [null, Validators.required],
            normalPrices: this._formBuilder.array([
                this.initPriceFormGroup()
            ]),
            peakPrices: this._formBuilder.array([
                this.initPriceFormGroup()
            ]),
            offPeakPrices: this._formBuilder.array([
                this.initPriceFormGroup()
            ])
        });
        this.normalPrices = <FormArray>this.subLocationForm.controls['normalPrices'];
        this.peakPrices = <FormArray>this.subLocationForm.controls['peakPrices'];
        this.offPeakPrices = <FormArray>this.subLocationForm.controls['offPeakPrices'];
        // this.subLocationForm.valueChanges.subscribe(() => {
        //     console.log(this.subLocationForm);
        // });
    }

    ngOnInit() {
        this.globalService.globalData.subscribe(res => {
            this.globalData = res;
            this.globalData.organization.prices.forEach(price => {
                if (price.type === 'normal') {
                    this.normalPriceList.push(price);
                } else if (price.type === 'peak') {
                    this.peakPriceList.push(price);
                } else if (price.type === 'off-peak') {
                    this.offPeakPriceList.push(price);
                }
            });
        });
    }

    initPriceFormGroup() {
        return this._formBuilder.group({
            id: [null, Validators.required],
            value: [null, Validators.required]
        });
    }

    addNormalPrice() {
        this.normalPrices.push(this.initPriceFormGroup());
    }

    deleteNormalPrice(i) {
        this.normalPrices.removeAt(i);
    }

    addPeakPrice() {
        this.peakPrices.push(this.initPriceFormGroup());
    }

    deletePeakPrice(i) {
        this.peakPrices.removeAt(i);
    }

    addOffPeakPrice() {
        this.offPeakPrices.push(this.initPriceFormGroup());
    }

    deleteOffPeakPrice(i) {
        this.offPeakPrices.removeAt(i);
    }

    submit() {
        const formValue = this.subLocationForm.value;
        const body = { ...formValue };
        body.size += (' ' + this.sizeUnit);
        this.addedSubLocation.next(body);
    }

    ngOnDestroy() {
        console.log('destroy add sub location');
    }

}
