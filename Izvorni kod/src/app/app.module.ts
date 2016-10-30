import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';

// used to create fake backend
import { fakeBackendProvider } from './_helpers/fake-backend';
import { MockBackend, MockConnection } from '@angular/http/testing';
import { BaseRequestOptions } from '@angular/http';

import { AppComponent } from './app';
import { routing } from './app.routing';

import { AuthGuard } from './common/auth.guard';
import { AuthenticationService } from './services/authentication.service';
import { UserService } from './services/user.service';

import { Home }  from './components/home';
import { Login } from './components/login';
import { Profile }  from './components/profile';
import { Signup } from './components/signup';

import { EqualValidator } from './equal-validator.directive';

@NgModule({
    declarations: [
      AppComponent,
      Home,
      Login,
      Profile,
      Signup,

      EqualValidator,
    ],
    imports: [
      BrowserModule,
      FormsModule,
      HttpModule,
      routing
    ],
    providers: [
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
