import {Component} from 'angular2/core';
import {AnimalComponent} from './animal.component';

@Component({
    selector: 'my-app',
    template: `
        <h1>Angular 2 - Zoo App</h1>
        <animals></animals>
        `,
    directives: [AnimalComponent]
})
export class AppComponent { }