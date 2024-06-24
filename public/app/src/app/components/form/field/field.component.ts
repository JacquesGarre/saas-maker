import { Component, Input } from '@angular/core';
import { FieldConfig } from './field-config.interface';
import { CommonModule } from '@angular/common';
import { FormGroup, FormsModule, ReactiveFormsModule } from '@angular/forms';

@Component({
  selector: 'app-field',
  standalone: true,
  imports: [
    FormsModule, ReactiveFormsModule, CommonModule, 
  ],
  templateUrl: './field.component.html',
  styleUrl: './field.component.scss'
})
export class FieldComponent {

  @Input() formGroup!: FormGroup;
  @Input() config!: FieldConfig;

}
