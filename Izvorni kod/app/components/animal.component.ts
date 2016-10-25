import {Component} from 'angular2/core';
import {AnimalService} from '../services/animal.service';

@Component({
    selector: 'animals',
    template: `
        <h2>Animal</h2>
        {{title}}
        <ul>
            <li *ngFor="#animal of animals">
                {{animal}}
            </li>
        </ul>
        `,
    providers: [AnimalService]
})

export class AnimalComponent {
    title: string = "Title of the animal list";
    animals;

    constructor(animalService : AnimalService){
        this.animals = animalService.getAnimals();
    }
}