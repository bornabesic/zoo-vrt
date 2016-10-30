import { Injectable } from '@angular/core';
import { Http, Headers, RequestOptions, Response } from '@angular/http';
import { jwt } from '../_helpers/jwt';

@Injectable()
export class AnimalService {
    private animalsUrl = '/api/animals';

    constructor(private http: Http) { }

    adopt(userId, animalId) {
        return this.http.post(this.animalsUrl + '/adopt', { '/userId': userId, '/animalId': animalId }, jwt())
                        .map((response: Response) => response.json());
    }

    create(user) {
        return this.http.post(this.animalsUrl + '/create', user, jwt())
                        .map((response: Response) => response.json());
    }

    delete(id) {
        return this.http.post(this.animalsUrl + '/delete', id, jwt())
                         .map((response: Response) => response.json());
    }

    getAll() {
        return this.http.get(this.animalsUrl, jwt())
                         .map((response: Response) => response.json());
    }

    getAdopted(userId) {
        return this.http.get(this.animalsUrl + '/adopted/' + userId, jwt())
                         .map((response: Response) => response.json());
    }

    getById(id) {
        return this.http.get(this.animalsUrl + id, jwt())
                         .map((response: Response) => response.json());
    }

    update(user) {
        return this.http.put(this.animalsUrl + user.id, user, jwt())
                         .map((response: Response) => response.json());
    }
}