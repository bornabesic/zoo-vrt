import {ModuleWithProviders} from '@angular/core';
import {Routes, RouterModule} from '@angular/router';

import { Home } from './components/home';
import { Login } from './components/login';
import { Profile } from './components/profile';
import { Register } from './components/register';

import { AuthGuard } from './_guards/index';

const appRoutes: Routes = [
    { path: '',          component: Home, canActivate: [AuthGuard]  },
    { path: 'login',     component: Login },
    { path: 'register',  component: Register },
    { path: 'profile',   component: Profile, canActivate: [AuthGuard] },

    // otherwise redirect to home
    { path: '**', redirectTo: '' }
];

export const routing: ModuleWithProviders = RouterModule.forRoot(appRoutes);