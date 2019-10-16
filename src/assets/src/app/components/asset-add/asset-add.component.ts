import { OnDestroy, OnInit, ViewChild, ElementRef } from '@angular/core';
import { NavService } from '@service/nav.service';
import { GlobalService } from '@service/global.service';
import { FormArray, FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { MatDialog, MatDialogConfig, MatSnackBar } from '@angular/material';
import { SubLocationAddComponent } from '@app/components/modals/sub-location-add/sub-location-add.component';
import { Overlay } from '@angular/cdk/overlay';
import { CustomFieldAddComponent } from '@app/components/modals/custom-field-add/custom-field-add.component';
import { GlobalData, Price } from '@app/models/global-data';
import { AssetService } from '@service/asset.service';
import { forkJoin } from 'rxjs';
import { ActivatedRoute, Router } from '@angular/router';
import { stringCompare } from '@app/shared/common/common.util';
import { ASSET_TYPE_BY_ID } from '@app/shared/common/common.constant';
import * as _ from 'lodash';

export abstract class AssetAddComponent implements OnInit, OnDestroy {
    @ViewChild('venueTypeAutoComplete') venueTypeAutoComplete: ElementRef;
    @ViewChild('eventTypeAutoComplete') eventTypeAutoComplete: ElementRef;
    @ViewChild('amenityAutoComplete') amenityAutoComplete: ElementRef;
    assetTypeId: number;
    globalData: GlobalData;
    assetForm: FormGroup;
    country: any;
    building: number;
    addSubLocationDialogConfig: MatDialogConfig;
    addCustomFieldDialogConfig: MatDialogConfig;
    subLocations: Array<any> = [];
    customFields: Array<any> = [];
    photos: Array<any> = [];
    normalPrices: FormArray;
    peakPrices: FormArray;
    offPeakPrices: FormArray;
    sizeUnits = ['sqft', 'sqmt', 'sqin', 'sqyd'];
    sizeUnit = 'sqft';
    normalPriceList: Array<Price> = [];
    peakPriceList: Array<Price> = [];
    offPeakPriceList: Array<Price> = [];
    ASSET_TYPE_BY_ID = ASSET_TYPE_BY_ID;
    selectedVenueTypes = [];
    selectedEventTypes = [];
    displayVenueTypes = [];
    displayEventTypes = [];
    selectedBuildingList = [];
    selectedAmenities = [];
    displayAmenities = [];
    venueTypeOptions = [
        {
            id: '1',
            name: 'Club',
            disabled: false
        },
        {
            id: '2',
            name: 'Auditorium',
            disabled: false
        },
        {
            id: '3',
            name: 'Bar',
            disabled: false
        }
    ];

    eventTypeOptions = [];

    buildingOptions = [];

    amenityOptions = [];
    loading = false;
    title = 'Add new asset';
    cover_photo;
    assetId;
    existingPhotos: Array<{ url: string, fileName: string, blob?: Blob }> = [];
    existingCover: Array<{ url: string, fileName: string, blob?: Blob }> = [];
    assetInfo;
    constructor(protected _formBuilder: FormBuilder,
        protected _overlay: Overlay,
        protected _matDialog: MatDialog,
        protected _route: ActivatedRoute,
        protected _router: Router,
        protected _snackBar: MatSnackBar,
        protected navService: NavService,
        protected globalService: GlobalService,
        protected assetService: AssetService) {

        navService.hideNavBar();
        this.assetForm = this._formBuilder.group({
            name: [null, Validators.required],
            asset_type_id: [null, Validators.required],
            size: [null, Validators.required],

            // Atrium space
            description: [null, Validators.required],
            floor_id: [null, Validators.required],
            tax_id: [null],
            // - Atrium space

            normalPrices: this._formBuilder.array([
                // this.initPriceFormGroup()
            ]),
            peakPrices: this._formBuilder.array([
                // this.initPriceFormGroup()
            ]),
            offPeakPrices: this._formBuilder.array([
                // this.initPriceFormGroup()
            ]),

            // Venue
            venueType: [null, []],
            eventType: [null, []],
            building: [{ value: null, disabled: true }, []],
            amenity: [null, []],
            status: ['unpublish', []]
            // also floor_id
            // - Venue
        });

        this.normalPrices = <FormArray>this.assetForm.controls['normalPrices'];
        this.peakPrices = <FormArray>this.assetForm.controls['peakPrices'];
        this.offPeakPrices = <FormArray>this.assetForm.controls['offPeakPrices'];

        this.assetForm.controls['venueType'].valueChanges
            .subscribe(value => {
                this.searchAutoComplete(value, 'venueType');
            });

        this.selectedBuildingList.push(this.buildingOptions[0]);

    }

    ngOnInit() {
        this.assetTypeId = +this._route.snapshot.queryParams['asset-type'];
        this.assetForm.controls['asset_type_id'].patchValue(this.assetTypeId);
        this.assetForm.controls['asset_type_id'].updateValueAndValidity();
        this.globalService.globalData.subscribe(res => {
            this.globalData = res;

            this.amenityOptions = _.cloneDeep(this.globalData['amenities']);
            this.venueTypeOptions = _.cloneDeep(this.globalData['venue_types']);
            this.buildingOptions = this.globalData['organizations'];


            Object.keys(this.globalData['events']).forEach(key => {
                this.globalData['events'][key].forEach(value => {
                    this.eventTypeOptions.push(_.cloneDeep(value));
                });
            });


            this.displayVenueTypes = this.venueTypeOptions.slice();
            this.displayEventTypes = this.eventTypeOptions.slice();
            this.displayAmenities = this.amenityOptions.slice();

            this.globalData.organization.prices.forEach(price => {
                if (price.type === 'normal') {
                    this.normalPriceList.push(price);
                } else if (price.type === 'peak') {
                    this.peakPriceList.push(price);
                } else if (price.type === 'off-peak') {
                    this.offPeakPriceList.push(price);
                }
            });
            // Edit Mode
            if (window.location.href.includes('edit') === true) {
                // Edit asset mode
                this.title = 'Edit asset';
                this.assetId = parseInt(this._route.snapshot.url[1].path);

                this.assetService.getAssetDetail(this.assetId)
                    .subscribe(assetInfo => {
                        this.assetInfo = assetInfo;
                        this.populateEditDocument(assetInfo);
                    });


            } else {
                // Create document
                this.normalPrices.push(this.initPriceFormGroup());
                this.peakPrices.push(this.initPriceFormGroup());
                this.offPeakPrices.push(this.initPriceFormGroup());

                this.assetTypeId = this.globalData.asset_types.filter(value =>
                    stringCompare(value.slug, this._route.snapshot.url[0].path))[0].id;

                this.country = this.globalData.organization.location;
                this.building = this.globalData.organization.building.id;
                this.assetForm.controls['building'].patchValue(this.globalData.organization.building.name);
                this.assetForm.controls['building'].updateValueAndValidity();

                // Set default taxes
                this.assetForm.controls['tax_id'].patchValue(this.globalData.organization.taxes[0].id);
                this.assetForm.controls['tax_id'].updateValueAndValidity();
            }
        });
        // this.assetForm.valueChanges.subscribe((control) => {
        //    .. console.log(control);
        // });
        this.checkSaveButton(this.assetTypeId);
        this.setDialogConfig();
    }

    populateEditDocument(assetInfo) {
        this.assetTypeId = assetInfo.asset_type_id;
        this.populateAutoCompleteFields(assetInfo);
        if (assetInfo.fields && assetInfo.fields.length > 0) {
            assetInfo.fields = assetInfo.fields.map(field => {
                const temp = field;
                temp.value = field.pivot.value;
                return temp;
            })
            this.customFields = assetInfo.fields;
        }

        const normalPriceData = assetInfo.prices.filter(price => price.type === 'normal');
        normalPriceData.forEach((data) => {
            this.patchPrice('normal', {
                id: data.id,
                value: data.pivot.price
            });
        });
        const peakPriceData = assetInfo.prices.filter(price => price.type === 'peak');
        peakPriceData.forEach((data, i) => {
            this.patchPrice('peak', {
                id: data.id,
                value: data.pivot.price
            });
        });
        const offPeakPriceData = assetInfo.prices.filter(price => price.type === 'off-peak');
        offPeakPriceData.forEach((data) => {
            this.patchPrice('offPeak', {
                id: data.id,
                value: data.pivot.price
            });
        });

        Object.keys(this.assetForm.controls).forEach(ctrl => {
            if (assetInfo[ctrl] && this.assetForm.controls[ctrl]) {
                if (ctrl === 'building') {
                    this.assetForm.controls[ctrl].patchValue(assetInfo[ctrl].name);
                } else if (ctrl === 'size') {
                    this.assetForm.controls[ctrl].patchValue(assetInfo[ctrl].value);
                } else {
                    this.assetForm.controls[ctrl].patchValue(assetInfo[ctrl]);
                }
                this.assetForm.controls[ctrl].updateValueAndValidity();
            }
        });

        this.getExistedPhotos(assetInfo);
    }

    getExistedPhotos(assetInfo) {
        this.existingPhotos = [];
        this.existingCover = [];
        const photoArr: Array<{ url: string, fileName: string, blob?: Blob }> = [],
            coverArr: Array<{ url: string, fileName: string, blob?: Blob }> = [];

        if (assetInfo.photos && assetInfo.photos.length) {
            assetInfo.photos.forEach(photo => {
                photoArr.push({
                    url: "http://demo.realestatedoc.co/" + photo.values.origin.uri,
                    fileName: photo.values.name
                })

            })
        }

        if (assetInfo.cover) {
            coverArr.push({
                url: "http://demo.realestatedoc.co/" + assetInfo.cover.values.origin.uri,
                fileName: assetInfo.cover.title
            })
        }

        this.existingPhotos = photoArr;
        this.existingCover = coverArr;
    }

    populateAutoCompleteFields(assetInfo) {
        const existedVenues = assetInfo.types;
        const existedEventTypes = assetInfo.events;
        const existedAmenity = assetInfo.amenities;

        existedVenues.forEach(venue => {
            const selectedVenue = this.displayVenueTypes.find(v => v.id === venue.id);
            this.selectAutoComplete({
                source: {
                    selected: true
                }
            }, selectedVenue, 'venueType');
        });

        existedEventTypes.forEach(event => {
            const selectedEvent = this.displayEventTypes.find(v => v.id === event.id);
            this.selectAutoComplete({
                source: {
                    selected: true
                }
            }, selectedEvent, 'eventType');
        });

        existedAmenity.forEach(amenity => {
            const selectedAmenity = this.displayAmenities.find(v => v.id === amenity.id);
            this.selectAutoComplete({
                source: {
                    selected: true
                }
            }, selectedAmenity, 'amenity');
        });
    }

    checkSaveButton(assetTypeId) {
        if (assetTypeId === this.ASSET_TYPE_BY_ID.VENUE) {
            const atriumSpaceFormField = ['asset_type_id', 'size'];

            atriumSpaceFormField.forEach(field => {
                this.assetForm.controls[field].setValidators([]);
                this.assetForm.controls[field].updateValueAndValidity();
            });
        }
    }

    setDialogConfig() {
        this.addSubLocationDialogConfig = {
            id: 'add-sub-location',
            panelClass: 'add-sub-location',
            width: '50%',
            height: '90%',
            disableClose: true,
            autoFocus: false,
        };

        this.addCustomFieldDialogConfig = {
            id: 'add-custom-field',
            panelClass: 'add-custom-field',
            width: '20%',
            disableClose: true,
            autoFocus: false
        };
    }

    openAddSubLocationDialog() {
        const dialogRef = this._matDialog.open(SubLocationAddComponent, this.addSubLocationDialogConfig);
        dialogRef.componentInstance.addedSubLocation.subscribe(subLocation => {
            this.subLocations.push(subLocation);
        });
    }

    openCustomFieldDialog() {
        const dialogRef = this._matDialog.open(CustomFieldAddComponent, this.addCustomFieldDialogConfig);
        dialogRef.componentInstance.addedCustomField.subscribe(customField => {
            this.customFields.push(customField);
        });
    }

    initPriceFormGroup(data?) {
        // return this._formBuilder.group({
        //     id: [data ? data.id : null, Validators.required],
        //     value: [data ? data.value : null, Validators.required]
        // });
        return this._formBuilder.group({
            id: [data ? data.id : null, []],
            value: [data ? data.value : null, []]
        });
    }

    addPrice(priceType) {
        let formControlName;
        if (priceType === 'normal') {
            formControlName = 'normalPrices';
        } else if (priceType === 'peak') {
            formControlName = 'peakPrices';
        } else if (priceType === 'offPeak') {
            formControlName = 'offPeakPrices';
        }
        this[formControlName].push(this.initPriceFormGroup());
    }

    patchPrice(priceType, data) {
        let formControlName;
        if (priceType === 'normal') {
            formControlName = 'normalPrices';
        } else if (priceType === 'peak') {
            formControlName = 'peakPrices';
        } else if (priceType === 'offPeak') {
            formControlName = 'offPeakPrices';
        }
        this[formControlName].push(this.initPriceFormGroup(data));
    }

    deletePrice(i, priceType) {
        let formControlName;
        let priceListName;
        if (priceType === 'normal') {
            formControlName = 'normalPrices';
            priceListName = 'normalPriceList';
        } else if (priceType === 'peak') {
            formControlName = 'peakPrices';
            priceListName = 'peakPriceList';
        } else if (priceType === 'offPeak') {
            formControlName = 'offPeakPrices';
            priceListName = 'offPeakPriceList';
        }

        // enable option after delete
        const priceIndex = this[priceListName].findIndex((item) => item.id === this[formControlName].controls[i].value.id);
        if (priceIndex > -1) {
            this[priceListName][priceIndex].disabled = false;
        }
        this[formControlName].removeAt(i);
    }

    disableOption(i, i2, priceType, event) {
        let formControlName;
        let priceListName;
        if (priceType === 'normal') {
            formControlName = 'normalPrices';
            priceListName = 'normalPriceList';
        } else if (priceType === 'peak') {
            formControlName = 'peakPrices';
            priceListName = 'peakPriceList';
        } else if (priceType === 'offPeak') {
            formControlName = 'offPeakPrices';
            priceListName = 'offPeakPriceList';
        }

        if (event.source.selected) {
            // disable selected option
            this[priceListName][i2].disabled = true;
        } else {
            // enable old option
            const priceIndex = this[priceListName].findIndex((item) => item.id === this[formControlName].controls[i].value.id);
            if (priceIndex > -1) {
                this[priceListName][priceIndex].disabled = false;
            }
        }
    }

    onChangedPhotos(data, isCoverPhoto?) {
        let photoId;
        if (!isCoverPhoto) {
            this.photos = [];
        }

        data.forEach((photo) => {
            photoId = photo.serverResponse ? photo.serverResponse.response.data.id : photo.id;
            if (isCoverPhoto) {
                this.cover_photo = photoId;
            } else {
                if (this.assetId) {
                    let _photo = this.assetInfo.photos.find(existPhoto => {
                        let url = 'http://demo.realestatedoc.co/' + existPhoto.values.origin.uri;
                        return url === photo.src
                    });
    
                    if (_photo) {
                        this.photos.push(_photo.id);
                    } else {
                        this.photos.push(photoId);
                    }
                } else {
                    this.photos.push(photoId);
                }
            }
        });
    }

    reduceForm(prices: Array<any>) {
        const result = {};
        prices.forEach(price => {
            if (price.id) {
                result[price.id] = price.value;
            }
        });
        return result;
    }

    newAsset() {
        this.loading = true;
        const formValue = this.assetForm.value;
        const body = { ...formValue };
        delete body.normalPrices;
        delete body.peakPrices;
        delete body.offPeakPrices;

        body.prices = {
            ...this.reduceForm(formValue.normalPrices),
            ...this.reduceForm(formValue.peakPrices),
            ...this.reduceForm(formValue.offPeakPrices)
        };

        body.fields = this.reduceForm(this.customFields);

        if (this.photos.length) {
            body.photos = this.photos;
        }

        if (this.cover_photo) {
            body.cover_photo = this.cover_photo;
        }

        body.size += (' ' + this.sizeUnit);

        // Venue fields
        body.building = this.building;
        body.events = this.selectedEventTypes.map(eventType => eventType.id);
        body.types = this.selectedVenueTypes.map(venue => venue.id);
        body.amenities = this.selectedAmenities.map(amenity => amenity.id);
        // - Venue Fields

        body.asset_type_id = this.assetTypeId;
        if (this.assetId) {
            // Edit, code for demo UOA
            body.id = this.assetId;
            this.assetService.putUpdateAsset(body).subscribe(res => {
                if (this.assetTypeId === this.ASSET_TYPE_BY_ID.VENUE) {
                    this.assetService.getAssetDetail(this.assetId)
                        .subscribe(assetInfo => {
                            this.assetInfo = assetInfo;
                            this.getExistedPhotos(assetInfo);
                            this.loading = false;
                            this._snackBar.open('Update successfully!', '', {
                                duration: 5000
                            });
                        });
                }

            }, err => {
                this.loading = false;
                this._snackBar.open('Error!', '', {
                    duration: 5000
                });
            });
        } else {
            this.assetService.postAddAsset(body).subscribe(res => {
                if (this.assetTypeId === this.ASSET_TYPE_BY_ID.VENUE) {
                    this._router.navigate(['asset', 'edit', 'venue', res.id]);
                } else {
                    this.newSubLocation(res.id);
                }
                this.loading = false;
                this._snackBar.open('Create successfully!', '', {
                    duration: 5000
                });
            }, err => {
                this.loading = false;
                this._snackBar.open('Error!', '', {
                    duration: 5000
                });
            });
        }
    }

    newSubLocation(assetId) {
        // console.log('come route navigate to detail');

        const requests = [];
        this.subLocations.forEach(subLocation => {
            const body = { ...subLocation };
            delete body.normalPrices;
            delete body.peakPrices;
            delete body.offPeakPrices;
            body.prices = {
                ...this.reduceForm(subLocation.normalPrices),
                ...this.reduceForm(subLocation.peakPrices),
                ...this.reduceForm(subLocation.offPeakPrices)
            };
            body.asset_type_id = this.assetTypeId;
            body.parent_asset_id = assetId;
            requests.push(this.assetService.postAddAsset(body));
        });
        forkJoin(requests).subscribe(res => {
            this._router.navigate(['asset', 'detail', assetId]);
        });

    }

    ngOnDestroy() {
        this.navService.showNavBar();
    }

    // Autocomplete functions
    selectAutoComplete(e, option, name) {
        if (option && e.source.selected === true) {
            if (name === 'venueType') {
                option.disabled = true;
                this.selectedVenueTypes.push(option);
                this.selectedVenueTypes = _.uniqBy(this.selectedVenueTypes, 'id');

            } else if (name === 'eventType') {
                option.disabled = true;
                this.selectedEventTypes.push(option);
                this.selectedEventTypes = _.uniqBy(this.selectedEventTypes, 'id');
            } else if (name === 'amenity') {
                option.disabled = true;
                this.selectedAmenities.push(option);
                this.selectedAmenities = _.uniqBy(this.selectedAmenities, 'id');
            }
        }
    }

    resetAutoComplete(name) {
        if (name === 'venueType') {
            this.assetForm.controls['venueType'].patchValue('');
            this.displayVenueTypes = this.venueTypeOptions.slice();
        } else if (name === 'eventType') {
            this.assetForm.controls['eventType'].patchValue('');
            this.displayEventTypes = this.eventTypeOptions.slice();
        } else if (name === 'amenity') {
            this.assetForm.controls['amenity'].patchValue('');
            this.displayAmenities = this.amenityOptions.slice();
        }
    }

    searchAutoComplete(value: string, name) {
        if (name === 'venueType') {
            this.displayVenueTypes = this.venueTypeOptions.slice();

            if (value) {
                this.displayVenueTypes = this.displayVenueTypes.filter(venueType => {
                    return (
                        venueType.name
                            .toLowerCase()
                            .includes(value.toLowerCase()) || venueType.name.toLowerCase().includes(value.toLowerCase())
                    );
                });
            }
        } else if (name === 'eventType') {
            this.displayEventTypes = this.eventTypeOptions.slice();

            if (value) {
                this.displayEventTypes = this.displayEventTypes.filter(eventType => {
                    return (
                        eventType.name
                            .toLowerCase()
                            .includes(value.toLowerCase()) || eventType.name.toLowerCase().includes(value.toLowerCase())
                    );
                });
            }
        } else if (name === 'amenity') {
            this.displayAmenities = this.amenityOptions.slice();

            if (value) {
                this.displayAmenities = this.displayAmenities.filter(amenity => {
                    return (
                        amenity.name
                            .toLowerCase()
                            .includes(value.toLowerCase()) || amenity.name.toLowerCase().includes(value.toLowerCase())
                    );
                });
            }
        }
    }

    removeAutoComplete(option, name) {
        if (name === 'venueType') {
            const venueIndex = this.selectedVenueTypes.findIndex(value => value.id === option.id);
            if (venueIndex > -1) {
                this.selectedVenueTypes[venueIndex].disabled = false;
                this.selectedVenueTypes.splice(
                    venueIndex, 1
                );
            }
        } else if (name === 'eventType') {
            const eventIndex = this.selectedEventTypes.findIndex(value => value.id === option.id);
            if (eventIndex > -1) {
                this.selectedEventTypes[eventIndex].disabled = false;
                this.selectedEventTypes.splice(
                    eventIndex, 1
                );
            }
        } else if (name === 'amenity') {
            const amenityIndex = this.selectedAmenities.findIndex(value => value.id === option.id);
            if (amenityIndex > -1) {
                this.selectedAmenities[amenityIndex].disabled = false;
                this.selectedAmenities.splice(
                    amenityIndex, 1
                );
            }
        }
    }

    blurAfterSelect(name) {
        if (name === 'venueType') {
            this.venueTypeAutoComplete.nativeElement.blur();
        } else if (name === 'eventType') {
            this.eventTypeAutoComplete.nativeElement.blur();
        } else if (name === 'amenity') {
            this.amenityAutoComplete.nativeElement.blur();
        }
    }

    // - Autocomplete functions
}
