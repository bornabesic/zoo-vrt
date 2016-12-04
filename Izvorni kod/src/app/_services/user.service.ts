import { Injectable } from '@angular/core';
import { Http, Headers, RequestOptions, Response } from '@angular/http';
import { jwt } from '../_helpers/jwt';

@Injectable()
export class UserService {
    private usersUrl = '/api/users';

    constructor(private http: Http) { }

    create(user) {
        return this.http.post(this.usersUrl + '/create', user, jwt())
                        .map((response: Response) => response.json());
    }

    delete(id) {
        return this.http.post(this.usersUrl + '/delete', id, jwt())
                        .map((response: Response) => response.json());
    }

    getAll() {
        return this.http.get(this.usersUrl, jwt())
                        .map((response: Response) => response.json());
    }

    getById(id) {
        return this.http.get(this.usersUrl + '/getById/' + id, jwt())
                        .map((response: Response) => response.json());
    }

    update(user) {
        return this.http.put(this.usersUrl + '/update/' + user.id, user, jwt())
                        .map((response: Response) => response.json());
    }
}