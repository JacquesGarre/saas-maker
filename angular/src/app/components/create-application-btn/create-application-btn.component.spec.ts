import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CreateApplicationBtnComponent } from './create-application-btn.component';

describe('CreateApplicationBtnComponent', () => {
  let component: CreateApplicationBtnComponent;
  let fixture: ComponentFixture<CreateApplicationBtnComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [CreateApplicationBtnComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(CreateApplicationBtnComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
