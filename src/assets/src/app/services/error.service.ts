import { Injectable } from '@angular/core';
import { HttpErrorResponse } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
    providedIn: 'root'
})

export class ErrorService {


    handleRequestError(error: HttpErrorResponse) {
        return Observable.throw(error);
    }
}
