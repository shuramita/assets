import { Component, EventEmitter, OnDestroy, OnInit, Output } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { GlobalService } from '@service/global.service';
import { MatDialogRef } from '@angular/material';
import { GlobalData } from '@app/models/global-data';
import { AssetService } from '@service/asset.service';

@Component({
    selector: 'app-custom-field-setting',
    templateUrl: './custom-field-setting-add.component.html',
    styleUrls: ['./custom-field-setting-add..component.scss']
})
export class CustomFieldSettingAddComponent implements OnInit, OnDestroy {
    @Output() addedCustomField: EventEmitter<any> = new EventEmitter();
    globalData: GlobalData;

    types = [
        {
            key: 'string',
            text: 'String'
        },
        {
            key: 'text',
            text: 'Text'
        },
        {
            key: 'number',
            text: 'Number'
        }
    ];

    customFieldForm: FormGroup;

    constructor(private _dialogRef: MatDialogRef<CustomFieldSettingAddComponent>,
        private _formBuilder: FormBuilder,
        private assetService: AssetService,
        private globalService: GlobalService) {

        this.customFieldForm = this._formBuilder.group({
            key: [null, Validators.required],
            title: [null, Validators.required],
            value: [null, Validators.required],
            type: [null, Validators.required],
            description: [null]
        });
    }

    ngOnInit() {
        this.globalService.globalData.subscribe(res => {
            this.globalData = res;
        });
    }

    submit() {
        const formValue = this.customFieldForm.value;
        this.assetService.postAddField(formValue).subscribe(() => {
            this._dialogRef.close();
        });
    }

    ngOnDestroy() {
        console.log('destroy custom field component');
    }

}
