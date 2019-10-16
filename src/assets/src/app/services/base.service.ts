import { Injectable } from '@angular/core';
import { HttpClient, HttpErrorResponse, HttpHeaders, HttpParams } from '@angular/common/http';
import { ErrorService } from './error.service';
import { Observable } from 'rxjs';
import { catchError, map, timeout } from 'rxjs/operators';
import { AppConstant } from '../app.constant';
import {environment} from '../../environments/environment';

const tokenTag = document.head.querySelector('meta[name="api-token"]');
const token = tokenTag ? tokenTag['content'] : AppConstant.API_KEY;
console.log(token);
if ( token === '' ) {
    window.location.href = `${environment.api}${environment.loginURL}`;
}
@Injectable({
    providedIn: 'root'
})
export class BaseService {
    protected apiHost = AppConstant.API_HOST;
    protected apiUrlPrefix = AppConstant.API_PREFIX;
    protected headers: HttpHeaders;

    constructor(protected http: HttpClient,
        protected errorService: ErrorService) {

        this.headers = new HttpHeaders({
            'Content-Type': 'application/json',
            'Access-Control-Max-Age': '3600',
            'Authorization': `Bearer ${token}`
        });
    }

    public static mapPaths(paths: Array<any>): string {
        return paths.join('/');
    }

    public createAPIURL(path: string): string {
        return this.apiHost + this.apiUrlPrefix + path;
    }

    public get(url: string, params?: HttpParams | any): Observable<Object | any> {
        const fullUrl = this.createAPIURL(url);
        return this.http.get(fullUrl, { headers: this.headers, params: params })
                   .pipe(
                       map((response: any) => {
                           return response.data;
                       }),
                       timeout(AppConstant.TIMEOUT_REQUEST),
                       catchError((error: HttpErrorResponse) => {
                           return this.errorService.handleRequestError(error);
                       })
                   );

    }

    public post(url: string, body?: any): Observable<Object | any> {
        const fullUrl = this.createAPIURL(url);
        return this.http.post(fullUrl, body, { headers: this.headers })
                   .pipe(
                       map((response: any) => {
                           return response.data;
                       }),
                       timeout(AppConstant.TIMEOUT_REQUEST),
                       catchError((error: HttpErrorResponse) => {
                           return this.errorService.handleRequestError(error);
                       })
                   );
    }

    public put(url: string, body?: any): Observable<Object | any> {
        const fullUrl = this.createAPIURL(url);
        return this.http.put(fullUrl, body, { headers: this.headers })
                   .pipe(
                       map((response: any) => {
                           return response.data;
                       }),
                       timeout(AppConstant.TIMEOUT_REQUEST),
                       catchError((error: HttpErrorResponse) => {
                           return this.errorService.handleRequestError(error);
                       })
                   );
    }

    public remove(url: string, params?: HttpParams | any): Observable<Object | any> {
        const fullUrl = this.createAPIURL(url);
        return this.http.delete(fullUrl, { headers: this.headers, params: params })
                   .pipe(
                       map((response: any) => {
                           return response.data;
                       }),
                       timeout(AppConstant.TIMEOUT_REQUEST),
                       catchError((error: HttpErrorResponse) => {
                           return this.errorService.handleRequestError(error);
                       })
                   );
    }
}
