import { Component, OnInit } from '@angular/core';
import { User } from './user';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit {
    public user: User;

    ngOnInit() {
        // initialize model here
        this.user = {
            username: '',
            email: '',
            password: '',
            confirmPassword: ''
        }
    }

    save(model: User, isValid: boolean) {
        // call API to save customer
        console.log(model, isValid);
    }
}