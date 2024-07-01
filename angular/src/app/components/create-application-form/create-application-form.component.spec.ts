import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CreateApplicationFormComponent } from './create-application-form.component';

describe('CreateApplicationFormComponent', () => {
  let component: CreateApplicationFormComponent;
  let fixture: ComponentFixture<CreateApplicationFormComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [CreateApplicationFormComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(CreateApplicationFormComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
