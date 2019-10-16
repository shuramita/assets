import { Injectable } from '@angular/core';
import Echo from 'laravel-echo';
import * as io_ from 'socket.io-client';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

const token = document.head.querySelector('meta[name="csrf-token"]');
const apiKey = document.head.querySelector('meta[name="api-token"]');

window['io'] = io_;

@Injectable({
  providedIn: 'root'
})
export class NotificationService {
  public notifications;

    constructor() {
    }

    public EchoService() {
      return new Echo({
          broadcaster: 'socket.io',
                          host: window.location.origin + ':6001',
                          auth: {
              headers: {
                  Authorization: 'Bearer ' + apiKey['content'],
              },
          },
      });
  }
}
