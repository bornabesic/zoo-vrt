import { Injectable } from '@angular/core';

@Injectable()
export class AnimalService {
    getAnimals() : string[] {
        return ["Giraffe", "Lion", "Seal"];
    }
}