import { Component, EventEmitter, OnDestroy, OnInit, Output } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { GlobalService } from '@service/global.service';
import { MatDialogRef } from '@angular/material';
import { GlobalData } from '@app/models/global-data';
import { AssetService } from '@service/asset.service';

@Component({
    selector: 'app-price-type',
    templateUrl: './price-type-add.component.html',
    styleUrls: ['./price-type-add.component.scss']
})
export class PriceTypeAddComponent implements OnInit, OnDestroy {
    @Output() addedPrice: EventEmitter<any> = new EventEmitter();
    globalData: GlobalData;

    priceType = [
        {
            key: 'normal',
            text: 'Normal'
        },
        {
            key: 'peak',
            text: 'Peak'
        },
        {
            key: 'off-peak',
            text: 'Off Peak'
        }
    ];
    priceOptions = [
        {
            unit: {
                key: 'hourly',
                text: 'Hourly'
            },
            availableAt: [
                {
                    key: 'hourInDay',
                    text: 'Hour In Day'
                }
            ]
        },
        {
            unit: {
                key: 'daily',
                text: 'Daily'
            },
            availableAt: [
                {
                    key: 'hourInDay',
                    text: 'Hour In Day'
                },
                {
                    key: 'dateInWeek',
                    text: 'Date In Week'
                },
                {
                    key: 'customDate',
                    text: 'Custom Date'
                }
            ]
        },
        {
            unit: {
                key: 'nightly',
                text: 'Nightly'
            },
            availableAt: [
                {
                    key: 'hourInDay',
                    text: 'Hour In Day'
                },
                {
                    key: 'dateInWeek',
                    text: 'Date In Week'
                },
                {
                    key: 'customDate',
                    text: 'Custom Date'
                }
            ]
        },
        {
            unit: {
                key: 'monthly',
                text: 'Monthly'
            },
            availableAt: [
                {
                    key: 'monthInYear',
                    text: 'Month In Year'
                },
                {
                    key: 'customMonth',
                    text: 'Custom Month'
                }
            ]
        },
        {
            unit: {
                key: 'quarterly',
                text: 'Quarterly'
            },
            availableAt: []
        },
        {
            unit: {
                key: 'yearly',
                text: 'Yearly'
            },
            availableAt: []
        }
    ];
    priceAvailableAt = [];
    available: any;

    hourInDay = [
        {
            key: 0,
            text: '0:00 AM'
        },
        {
            key: 1,
            text: '1:00 AM'
        },
        {
            key: 2,
            text: '2:00 AM'
        },
        {
            key: 3,
            text: '3:00 AM'
        },
        {
            key: 4,
            text: '4:00 AM'
        },
        {
            key: 5,
            text: '5:00 AM'
        },
        {
            key: 6,
            text: '6:00 AM'
        },
        {
            key: 7,
            text: '7:00 AM'
        },
        {
            key: 8,
            text: '8:00 AM'
        },
        {
            key: 9,
            text: '9:00 AM'
        },
        {
            key: 10,
            text: '10:00 AM'
        },
        {
            key: 11,
            text: '11:00 AM'
        },
        {
            key: 12,
            text: '12:00 PM'
        },
        {
            key: 13,
            text: '1:00 PM'
        },
        {
            key: 14,
            text: '2:00 PM'
        },
        {
            key: 15,
            text: '3:00 PM'
        },
        {
            key: 16,
            text: '4:00 PM'
        },
        {
            key: 17,
            text: '5:00 PM'
        },
        {
            key: 18,
            text: '6:00 PM'
        },
        {
            key: 19,
            text: '7:00 PM'
        },
        {
            key: 20,
            text: '8:00 PM'
        },
        {
            key: 21,
            text: '9:00 PM'
        },
        {
            key: 22,
            text: '10:00 PM'
        },
        {
            key: 23,
            text: '11:00 PM'
        }
    ];
    dateInWeek = [

        {
            key: 0,
            text: 'Monday'
        },
        {
            key: 1,
            text: 'Tuesday'
        },
        {
            key: 2,
            text: 'Wednesday'
        },
        {
            key: 3,
            text: 'Thursday'
        },
        {
            key: 4,
            text: 'Friday'
        },
        {
            key: 5,
            text: 'Saturday'
        },
        {
            key: 6,
            text: 'Sunday'
        },
    ];
    monthInYear = [
        {
            key: 0,
            text: 'January'
        },
        {
            key: 1,
            text: 'February'
        },
        {
            key: 2,
            text: 'March'
        },
        {
            key: 3,
            text: 'April'
        },
        {
            key: 4,
            text: 'May'
        },
        {
            key: 5,
            text: 'June'
        },
        {
            key: 6,
            text: 'July'
        },
        {
            key: 7,
            text: 'August'
        },
        {
            key: 8,
            text: 'September'
        },
        {
            key: 9,
            text: 'October'
        },
        {
            key: 10,
            text: 'November'
        },
        {
            key: 11,
            text: 'December'
        },
    ];

    priceForm: FormGroup;

    constructor(private _dialogRef: MatDialogRef<PriceTypeAddComponent>,
        private _formBuilder: FormBuilder,
        private assetService: AssetService,
        private globalService: GlobalService) {

        this.priceForm = this._formBuilder.group({
            name: [null, Validators.required],
            type: [null, Validators.required],
            unit: [null, Validators.required],
            available_at: [null, Validators.required],
            range: [null, Validators.required],
            description: [null, Validators.required],
        });
    }

    ngOnInit() {
        this.globalService.globalData.subscribe(res => {
            this.globalData = res;
        });
    }

    onChangeUnit(event) {
        const priceOption = this.priceOptions.find((option) =>
            option.unit.key === event.value
        );
        this.priceAvailableAt = priceOption ? priceOption.availableAt : [];
        this.available = null;

    }

    onChangeAvailableOptions(event) {
        this.available = event.value;
    }

    submit() {
        const formValue = this.priceForm.value;
        this.assetService.postAddPrice(formValue).subscribe(() => {
            this._dialogRef.close();
        });
    }

    ngOnDestroy() {
        console.log('destroy add price component');
    }

}
