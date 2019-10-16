import { Component } from '@angular/core';
import { NavService } from '@service/nav.service';
import { GlobalService } from '@service/global.service';
import { FormBuilder } from '@angular/forms';
import { MatDialog, MatSnackBar } from '@angular/material';
import { Overlay } from '@angular/cdk/overlay';
import { AssetService } from '@service/asset.service';
import { ActivatedRoute, Router } from '@angular/router';
import { AssetAddComponent } from '@app/components';

@Component({
    selector: 'app-asset-add-venue',
    templateUrl: '../asset-add.component.html',
    styleUrls: ['../asset-add.component.scss']
})
export class AssetAddVenueComponent extends AssetAddComponent {

    constructor(protected _formBuilder: FormBuilder,
        protected _overlay: Overlay,
        protected _matDialog: MatDialog,
        protected _route: ActivatedRoute,
        protected _router: Router,
        protected _snackBar: MatSnackBar,
        protected navService: NavService,
        protected globalService: GlobalService,
        protected assetService: AssetService) {
        super(_formBuilder, _overlay, _matDialog, _route, _router, _snackBar, navService, globalService, assetService)
    }
}
