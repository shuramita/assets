import { Component, OnDestroy, OnInit } from '@angular/core';
import { NavService } from '@service/nav.service';
import { GlobalService } from '@service/global.service';
import { GlobalData } from '@app/models/global-data';
import { AssetService } from '@service/asset.service';
import { ActivatedRoute } from '@angular/router';
import { AppConstant } from '@app/app.constant';
import { NgxGalleryAnimation, NgxGalleryImage, NgxGalleryOptions } from 'ngx-gallery';
import {HelperService} from '@service/helper.service';

@Component({
    selector: 'app-asset-detail',
    templateUrl: './asset-detail.component.html',
    styleUrls: ['./asset-detail.component.scss']
})
export class AssetDetailComponent implements OnInit, OnDestroy {
    assetId: number;
    data: any;
    globalData: GlobalData;
    normalPrices = [];
    peakPrices = [];
    offPeakPrices = [];
    subLocations = [];
    photos = [];


    galleryOptions: NgxGalleryOptions[];
    galleryImages: NgxGalleryImage[];

    constructor(private _route: ActivatedRoute,
        private navService: NavService,
        private globalService: GlobalService,
        private assetService: AssetService,
        public helper: HelperService
    ) {
        navService.hideNavBar();
    }

    ngOnInit() {
        this.galleryOptions = [
            {
                width: '100%',
                height: '600px',
                thumbnailsColumns: 2,
                imageAnimation: NgxGalleryAnimation.Slide
            }
        ];

        this.galleryImages = [];
        this.assetId = this._route.snapshot.params['id'];

        this.globalService.globalData.subscribe(res => {
            this.globalData = res;
        });

        this.assetService.getAssetDetail(this.assetId).subscribe(res => {
            this.data = res;
            this.data.prices.forEach(price => {
                if (price.type === 'normal') {
                    this.normalPrices.push(price);
                } else if (price.type === 'peak') {
                    this.peakPrices.push(price);
                } else if (price.type === 'off-peak') {
                    this.offPeakPrices.push(price);
                }
            });

            this.data.photos.forEach(photo => {
                this.galleryImages.push(
                    {
                        small: AppConstant.API_HOST + '/' + photo.values.mini.uri,
                        medium: AppConstant.API_HOST + '/' + photo.values.medium.uri,
                        big: AppConstant.API_HOST + '/' + photo.values.large.uri
                    });
            });

            if (this.data.childs && this.data.childs.length) {
                this.data.childs.map(child => {
                    child.normalPrices = [];
                    child.peakPrices = [];
                    child.offPeakPrices = [];
                    child.prices.forEach(price => {
                        if (price.type === 'normal') {
                            child.normalPrices.push(price);
                        } else if (price.type === 'peak') {
                            child.peakPrices.push(price);
                        } else if (price.type === 'off-peak') {
                            child.offPeakPrices.push(price);
                        }
                    });
                });
                this.subLocations = this.data.childs;
            }
        });
    }

    ngOnDestroy() {
        this.navService.showNavBar();
    }

}
