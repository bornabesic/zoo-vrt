import { Component, OnInit } from '@angular/core';

import {AuthenticationService} from './services/authentication.service';

@Component({
  selector: 'app-root',
  template: `
    <div class="container">
        <router-outlet></router-outlet>
    </div>
    `,
    providers: [AuthenticationService]
})
export class AppComponent {

    constructor(private _service:AuthenticationService){}

    logout() {
        this._service.logout();
    }
}