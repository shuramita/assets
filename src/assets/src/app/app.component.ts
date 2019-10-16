import { Component, AfterViewInit } from '@angular/core';
import {NavigationEnd, Router} from '@angular/router';
import {environment} from '../environments/environment';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements AfterViewInit {
    title = 'Asset Management';
    constructor(private router: Router) {
    }
    ngAfterViewInit(): void {
        this.router.events.subscribe(event => {
            if (event instanceof NavigationEnd) {
                (<any>window).gtag('config', environment.gaTrackingId, {'page_path': event.urlAfterRedirects});
            }
        });
    }

}
