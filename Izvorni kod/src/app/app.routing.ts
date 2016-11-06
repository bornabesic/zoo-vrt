import {ModuleWithProviders} from '@angular/core';
import {Routes, RouterModule} from '@angular/router';

import { Home } from './components/home';
import { Login } from './components/login';
import { Register } from './components/register';

import { AuthGuard } from './_guards/index';

const appRoutes: Routes = [
    { path: '',          component: Home, canActivate: [AuthGuard]  },
    { path: 'login',     component: Login },
    { path: 'register',  component: Register },

    // otherwise redirect to home
    { path: '**', redirectTo: '' }
];

export const routing: ModuleWithProviders = RouterModule.forRoot(appRoutes);