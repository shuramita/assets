import { Injectable } from '@angular/core';
import { HttpHeaders } from '@angular/common/http';
import { BaseService } from '@service/base.service';

@Injectable({
    providedIn: 'root'
})
export class ImageService extends BaseService {
    static UPLOAD = 'upload';

    public postImage(image: File) {
        const headers = new HttpHeaders({
            'Access-Control-Max-Age': '3600',
            'Authorization': this.headers.get('Authorization')
        });
        const url = this.createAPIURL(ImageService.UPLOAD);
        const path = BaseService.mapPaths([ImageService.UPLOAD]);

        const formData = new FormData();
        formData.append('file', image);
        formData.append('action', 'uploadPhotos');

        return this.http.post(url, formData, { headers: headers });
    }
}
