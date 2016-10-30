import {ModuleWithProviders} from '@angular/core';
import {Routes, RouterModule} from '@angular/router';

import { Home } from './components/home';
import { Login } from './components/login';
import { Signup } from './components/signup';
import { Profile } from './components/profile';

import { AuthGuard } from './common/auth.guard';

const appRoutes: Routes = [
    { path: '',        component: Login },
    { path: 'login',   component: Login },
    { path: 'signup',  component: Signup },
    { path: 'profile', component: Profile, canActivate: [AuthGuard] },
    { path: 'home',    component: Home, canActivate: [AuthGuard] },
    { path: '**',      component: Login },
];

export const routing: ModuleWithProviders = RouterModule.forRoot(appRoutes);