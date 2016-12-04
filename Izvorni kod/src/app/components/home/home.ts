import { Component, OnInit } from '@angular/core';

import { User } from '../user';
import { UserService } from '../../_services/index';
import { Router } from '@angular/router';
@Component({
    templateUrl: './home.html',
    styleUrls: ['./home.css']
})

export class Home implements OnInit {
    currentUser: User;
    users: User[] = [];

    constructor(private userService: UserService, private router: Router) {
        this.currentUser = JSON.parse(localStorage.getItem('currentUser'));
    }

    ngOnInit() {
        this.loadAllUsers();
    }

    deleteUser(id) {
        this.userService.delete(id).subscribe(() => { this.loadAllUsers() });
    }

    private loadAllUsers() {
        this.userService.getAll().subscribe(users => { this.users = users; });
    }

      logOut(){
    let link = ['/login'];
    this.router.navigate(link);
    }
}