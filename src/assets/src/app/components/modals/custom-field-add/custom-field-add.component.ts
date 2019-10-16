import { Component, EventEmitter, Input, OnDestroy, OnInit, Output } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { NavService } from '@service/nav.service';
import { GlobalService } from '@service/global.service';
import { MatDialogRef } from '@angular/material';
import { GlobalData } from '@app/models/global-data';

@Component({
    selector: 'app-custom-field-add',
    templateUrl: './custom-field-add.component.html',
    styleUrls: ['./custom-field-add.component.scss']
})
export class CustomFieldAddComponent implements OnInit, OnDestroy {
    @Input() assetTypeId: number;
    @Output() addedCustomField: EventEmitter<any> = new EventEmitter();
    globalData: GlobalData;
    fieldType: string;
    customFieldForm: FormGroup;
    customFields: any;

    constructor(private _dialogRef: MatDialogRef<CustomFieldAddComponent>,
        private _formBuilder: FormBuilder,
        private navService: NavService,
        private globalService: GlobalService) {

        this.customFieldForm = this._formBuilder.group({
            id: [null, Validators.required],
            value: [null, Validators.required],
        });
    }

    ngOnInit() {
        this.globalService.globalData.subscribe(res => {
            this.globalData = res;
            this.customFields = this.globalData.organization.fields;
        });
    }

    changeField(event) {
        const defaultValue = this.customFields.find((field) => field.id === event.value);
        this.fieldType = defaultValue.type;
        this.customFieldForm.controls['value'].setValue(defaultValue.value);
    }

    submit() {
        this.addedCustomField.next(this.customFieldForm.value);
    }

    ngOnDestroy() {
        console.log('destroy custom field component');
    }

}
