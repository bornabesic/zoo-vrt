import { BrowserModule } from '@angular/platform-browser';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';
import { NgModule } from '@angular/core';

// used to create fake backend
import { fakeBackendProvider } from './_helpers/fake-backend';
import { MockBackend, MockConnection } from '@angular/http/testing';
import { BaseRequestOptions } from '@angular/http';
import { MaterialModule } from '@angular/material';

import { AppComponent } from './app';
import { routing } from './app.routing';

import { AuthGuard } from './_guards/index';
import { AlertService, AuthenticationService, UserService } from './_services/index';

import { Alert } from './_directives/index';
import { Home }  from './components/home';
import { Login } from './components/login';
import { Register } from './components/register';

@NgModule({
    declarations: [
        AppComponent,
        Alert,
        Home,
        Login,
        Register
    ],
    imports: [
        BrowserModule,
        FormsModule,
        HttpModule,
        routing,
        MaterialModule.forRoot()
    ],
    providers: [
        AlertService,
        AuthGuard,
        AuthenticationService,
        UserService,

        // providers used to create fake backend
        fakeBackendProvider,
        MockBackend,
        BaseRequestOptions
    ],
    bootstrap: [AppComponent]
})
export class AppModule { }
