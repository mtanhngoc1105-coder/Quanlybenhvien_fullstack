import { createRouter, createWebHistory } from "vue-router";

import Home from "@/pages/Home.vue";
import CarouselGallery from "@/pages/CarouselGallery.vue";

import DoctorIndex from "@/pages/doctor/doctor_index.vue";
import DoctorDetail from "@/pages/doctor/doctor_detail.vue";

import DepartmentIndex from "@/pages/department/department_index.vue";
import DepartmentDetail from "@/pages/department/department_detail.vue";


import AppointmentIndex from "@/pages/appointment/appointment_index.vue";
import AppointmentDetail from "@/pages/appointment/appointment_detail.vue";

import MedicineIndex from "@/pages/medicine/medicine_index.vue";
import MedicineDetail from "@/pages/medicine/medicine_detail.vue";

import PatientIndex from "@/pages/patient/patient_index.vue";
import PatientDetail from "@/pages/patient/patient_detail.vue";

import PrescriptionIndex from "@/pages/prescription/prescription_index.vue";
import PrescriptionDetail from "@/pages/prescription/prescription_detail.vue";

const routes = [
  // HOME
  {
    path: "/",
    component: Home,
  },

  // CAROUSEL GALLERY
  {
    path: "/gallery",
    component: CarouselGallery,
  },

  // DOCTOR
  {
    path: "/doctors",
    component: DoctorIndex,
  },
  {
    path: "/doctors/:id",
    component: DoctorDetail,
    props: true,
  },

  // DEPARTMENT
  {
    path: "/departments",
    component: DepartmentIndex,
  },
  {
    path: "/departments/:id",
    component: DepartmentDetail,
    props: true,
  },

  
  {
    path: "/appointments",
    component: AppointmentIndex,
  },
  {
    path: "/appointments/:id",
    component: AppointmentDetail,
    props: true,
  },

{
  path: "/medicines",
  component: MedicineIndex,
},
{
  path: "/medicines/:id",
  component: MedicineDetail,
  props: true,
},

{
  path: "/patients",
  component: PatientIndex,
},
{
  path: "/patients/:id",
  component: PatientDetail,
  props: true,
},

{
  path: "/prescriptions",
  component: PrescriptionIndex,
},
{
  path: "/prescriptions/:id",
  component: PrescriptionDetail,
  props: true,
},
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;