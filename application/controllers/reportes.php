<?php

class reportes extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('usuarios_model');
        $this->load->model('f007_model');
        $this->load->model('f044_model');
        $this->load->model('f009_model');
        $this->load->model('f019_model');
        $this->load->model('f021_model');
        $this->load->model('procesos_model');
        $this->load->model('repositorio_model');
        $this->load->model('evaluacion_model');

        $this->load->model('evaluacion_examinadores_model');
    }

    public function reporte_f035($id) {

        $datos['EDUCACION'] = $this->usuarios_model->get_educacion($id);
        $datos['PERSONA'] = $this->usuarios_model->get_personas($id);
        $datos['F_PEDAGOGICA'] = $this->usuarios_model->get_fPedagogica($id);
        $datos['F_ESPE'] = $this->usuarios_model->get_fEspecializada($id);
        $datos['E_ESPE'] = $this->usuarios_model->get_Eprofesional($id);
        $datos['E_PROF'] = $this->usuarios_model->get_EPedagogica($id);
        $datos['REFERENCIAS'] = $this->usuarios_model->get_referencias_personales($id);
        $dato = $this->repositorio_model->get_respositorio($id, "1");

        if (!$dato) {
            $this->repositorio_model->insertar_persona($id, "1");
        }
        // Load library
        $this->load->library('dompdf_gen');
        $this->load->view('reportes/f035', $datos);
        // Get output html
        $html = $this->output->get_output();
        // Convert to PDF
        $this->dompdf->load_html($html);
        $this->dompdf->render();
        $this->dompdf->stream("hoja_vida.pdf");
    }

    public function reporte_f011($id) {
        $this->load->model('evaluacion_model');
        $this->load->model('hoja_model');
        $data = $this->repositorio_model->get_respositorio($id, '6');

        if (!$data) {
            $this->repositorio_model->insertar_persona($id, '6');
        }

        $datos['REG2'] = $this->evaluacion_model->sub_categorias2();
        $datos['REG1'] = $this->evaluacion_model->sub_categorias1();
        $datos['REG3'] = $this->evaluacion_model->sub_categorias3();
        $datos['REG4'] = $this->evaluacion_model->sub_categorias4();
        $datos['PERSONAS'] = $this->hoja_model->get_calificar($id);
        $dato = $this->evaluacion_model->calificacion($id);

        foreach ($dato as $value) {
            $datos['ID'] = $value->ID_EVALUACION;
            $datos['VALOR1'] = $value->VALOR1;
            $datos['VALOR2'] = $value->VALOR2;
            $datos['VALOR3'] = $value->VALOR3;
            $datos['VALOR4'] = $value->VALOR4;
            $datos['FECHA'] = $value->FECHA;

            $datos['RESULTADO'] = ($value->SUMA);
        }

        // Load library
        $this->load->library('dompdf_gen');
        $this->load->view('reportes/f011', $datos);
        // Get output html
        $html = $this->output->get_output();
        // Convert to PDF
        $this->dompdf->load_html($html);
        $this->dompdf->render();
        $this->dompdf->stream("F011.pdf");
    }

    public function reporte_f019($id_persona) {
        $datos['VER'] = $this->procesos_model->getVerificador("Analista de certificaci??n y control");
        $datos['CER'] = $this->procesos_model->getVerificador("Responsable de Proceso de Certificaci??n");
        $datos['PERSONAS'] = $this->f019_model->getPersonas($id_persona);
        $educacion = $this->f019_model->getDatos($id_persona,'tab_educacion_superior');
       
        
            
        $contador=0;
          if ( $educacion) {
            $datos['EDU_CUMPLE'] ="SI";
            $datos['EDU_OB'] ="Exito";
            $contador++;
            
        }  
        else {
             $datos['EDU_CUMPLE'] ="NO";
            $datos['EDU_OB'] ="Falta documento subir";
        }
        
        $hoja = $this->f019_model->getDatos($id_persona,'tab_respositorio_interno');
      
        
        if ( $hoja) {
            $datos['HOJA_CUMPLE'] ="SI";
            $datos['HOJA_OB'] ="Exito";
            $contador++;
            
        }  
        else {
             $datos['HOJA_CUMPLE'] ="NO";
            $datos['HOJA_OB'] ="Falta documento subir";
        }
        $exp = $this->f019_model->getDatos($id_persona,'tab_detalles_experiencias_profesionales','ID_EXPE_PROFESIONAL','2');
      
        
        if ($exp) {
            $datos['EXP_CUMPLE'] ="SI";
            $datos['EXP_OB'] ="Exito";
             $contador++;
            
        }  
        else {
             $datos['EXP_CUMPLE'] ="NO";
            $datos['EXP_OB'] ="Falta documento subir";
        }
        $exp1 = $this->f019_model->getDatos($id_persona,'tab_detalles_experiencias_profesionales','ID_EXPE_PROFESIONAL','1');
      
        
        if ($exp1) {
            $datos['EXP_CUMPLE1'] ="SI";
            $datos['EXP_OB1'] ="Exito";
             $contador++;
            
        } 
        else {
             $datos['EXP_CUMPLE1'] ="NO";
            $datos['EXP_OB1'] ="Falta documento subir";
        }
        
        $exp = $this->f019_model->getDatos($id_persona,'tab_detalles_formaciones','ID_FORMACION_GENERAL','1');
      
        
        if ($exp) {
            $datos['FOR_CUMPLE'] ="SI";
            $datos['FOR_OB'] ="Exito";
             $contador++;
            
        } 
         else {
             $datos['FOR_CUMPLE'] ="NO";
            $datos['FOR_OB'] ="Falta documento subir";
        }
        
        $exp = $this->f019_model->getDatos($id_persona,'tab_detalles_formaciones','ID_FORMACION_GENERAL','2');
      
        
        if ($exp) {
            $datos['FOR_CUMPLE1'] ="SI";
            $datos['FOR_OB1'] ="Exito";
             $contador++;
            
        }  
         else {
             $datos['FOR_CUMPLE1'] ="NO";
            $datos['FOR_OB1'] ="Falta documento subir";
        }
        $ind = $this->f019_model->getDatos($id_persona,'tab_respositorio_interno','ID_ARCHIVO','9');
      
        
        if ($ind) {
            $datos['IND_CUMPLE'] ="SI";
            $datos['IND_OB'] ="Exito";
             $contador++;
            
        }  
        else {
             $datos['IND_CUMPLE'] ="NO";
            $datos['IND_OB'] ="Falta documento subir";
        }
        $acuerdo = $this->f019_model->getDatos($id_persona,'tab_respositorio_interno','ID_ARCHIVO','3');
      
        
        if ($acuerdo) {
            $datos['ACUERDO_CUMPLE'] ="SI";
            $datos['ACUERDO_OB'] ="Exito";
             $contador++;
            
        }  else {
             $datos['ACUERDO_CUMPLE'] ="NO";
            $datos['ACUERDO_OB'] ="Falta documento subir";
        }
        
        $etica = $this->f019_model->getDatos($id_persona,'tab_respositorio_interno','ID_ARCHIVO','5');
      
        
        if ($etica) {
            $datos['ETICA_CUMPLE'] ="SI";
            $datos['ETICA_OB'] ="Exito";
             $contador++;
            
        }  else {
            $datos['ETICA_CUMPLE'] ="NO";
            $datos['ETICA_OB'] ="Falta documento subir";
             $contador++;
        }
        
        if ($contador>=9) {
            $this->f044_model->actualizar_personas($id_persona);
        }
        
        
           
            // Load library
            $this->load->library('dompdf_gen');
//        $this->dompdf->set_paper('A4', 'landscape');

            $this->dompdf->set_paper('A4', 'portrait');
            $this->load->view('reportes/f019', $datos);
            // Get output html
            $html = $this->output->get_output();

            // Convert to PDF
            $this->dompdf->load_html($html);
            $this->dompdf->render();

            $this->dompdf->stream("f019.pdf");
      
    }

    public function reporte_f021($id_persona) {

        $datos = $this->f021_model->getEvaluacion($id_persona);

        if ($datos) {
            
            $datos['PERSONAS'] = $this->f019_model->getPersonas($id_persona);
            $datos['RES'] = $this->procesos_model->getVerificador("Responsable de Proceso de Certificaci??n");
            $datos['COOR'] = $this->procesos_model->getVerificador("Coordinadora del Comit?? de Certificaci??n");
            $dato = $this->f021_model->getEvaluacion($id_persona);
            foreach ($dato as $value) {
                $datos['V1'] = $value->VALOR1;
                $datos['V2'] = $value->VALOR2;
                $datos['V3'] = $value->VALOR3;
                $datos['V4'] = $value->VALOR4;
                $datos['V5'] = $value->VALOR5;
                $datos['V6'] = $value->VALOR6;
                $datos['V7'] = $value->VALOR7;
                $datos['V8'] = $value->VALOR8;
                $datos['V9'] = $value->VALOR9;
                $datos['SUMA'] = $value->SUMA;

                $datos['ID'] = $value->EVA_DETALLE_PERSONA;
            }



            // Load library
            $this->load->library('dompdf_gen');
//        $this->dompdf->set_paper('A4', 'landscape');

            $this->dompdf->set_paper('A4', 'portrait');
            $this->load->view('reportes/f021', $datos);
            // Get output html
            $html = $this->output->get_output();

            // Convert to PDF
            $this->dompdf->load_html($html);
            $this->dompdf->render();

            $this->dompdf->stream("f021.pdf");
        } else {

            $this->load->library('dompdf_gen');
//        $this->dompdf->set_paper('A4', 'landscape');

            $this->dompdf->set_paper('A4', 'portrait');
            $this->load->view('reportes/vacio');
            // Get output html
            $html = $this->output->get_output();

            // Convert to PDF
            $this->dompdf->load_html($html);
            $this->dompdf->render();

            $this->dompdf->stream("f021.pdf");
        }
    }

    public function reporte_f002($id) {
        $dato['PERSONA'] = $this->usuarios_model->get_personas($id);
        $this->evaluacion_model->actulizarEstado($id, '4');
        $data = $this->repositorio_model->get_respositorio($id, '4');

        if (!$data) {
            $this->repositorio_model->insertar_persona($id, '4');
        }

        // Load library
        $this->load->library('dompdf_gen');
//        $this->dompdf->set_paper('A4', 'landscape');

        $this->dompdf->set_paper('A4', 'portrait');
        $this->load->view('reportes/f002', $dato);

        // Get output html
        $html = $this->output->get_output();

        // Convert to PDF
        $this->dompdf->load_html($html);
        $this->dompdf->render();


        $this->dompdf->stream("f002.pdf");
    }

    public function reporte_f010($id) {
        $dato['PERSONA'] = $this->usuarios_model->get_personas($id);
        $this->evaluacion_model->actulizarEstado($id, '6');
        $data = $this->repositorio_model->get_respositorio($id, '5');

        if (!$data) {
            $this->repositorio_model->insertar_persona($id, '5');
        }

        // Load library
        $this->load->library('dompdf_gen');
//        $this->dompdf->set_paper('A4', 'landscape');

        $this->dompdf->set_paper('A4', 'portrait');
        $this->load->view('reportes/f010', $dato);
        // Get output html
        $html = $this->output->get_output();

        // Convert to PDF
        $this->dompdf->load_html($html);
        $this->dompdf->render();

        $this->dompdf->stream("f010.pdf");
    }

    public function reporte_f003($id) {
        $dato['PERSONA'] = $this->usuarios_model->get_personas($id);


        $this->evaluacion_model->actulizarEstado($id, '5');
        $data = $this->repositorio_model->get_respositorio($id, '3');

        if (!$data) {
            $this->repositorio_model->insertar_persona($id, '3');
        }


        // Load library
        $this->load->library('dompdf_gen');
//        $this->dompdf->set_paper('A4', 'landscape');

        $this->dompdf->set_paper('A4', 'portrait');
        $this->load->view('reportes/f003', $dato);
        // Get output html
        $html = $this->output->get_output();

        // Convert to PDF
        $this->dompdf->load_html($html);
        $this->dompdf->render();

        $this->dompdf->stream("f003.pdf");
    }

    /*
      public function reporte_f009($id) {
      $dato['ENUNCIADO'] = $this->f009_model->f009();
      $dato['DETALLE'] = $this->f009_model->detalle($id);

      // Load library
      $this->load->library('dompdf_gen');
      //        $this->dompdf->set_paper('A4', 'landscape');

      $this->dompdf->set_paper('A4', 'portrait');
      $this->load->view('reportes/f009', $dato);
      // Get output html
      $html = $this->output->get_output();

      // Convert to PDF
      $this->dompdf->load_html($html);
      $this->dompdf->render();

      $this->dompdf->stream("f009.pdf");
      }
     */

    public function reporte_f044() {
        $datos['RECO'] = $this->f044_model->get_reconocimiento();
        $datos['TIPO'] = $this->f044_model->get_tipo_solicitud();
        $datos['S1'] = $this->f044_model->get_oec1();
        $datos['S2'] = $this->f044_model->get_oec2();
        $datos['S3'] = $this->f044_model->get_oec3();
        $datos['S4'] = $this->f044_model->get_oec4();
        $datos['EXAMINADOR'] = $this->f044_model->examinador();
        $datos['ESTUDIOS'] = $this->f044_model->estudios();
        $datos['EXPERIENCIA'] = $this->f044_model->experiencia();
        $datos['FORMACIONES'] = $this->f044_model->formacion();
        $datos['AULAS'] = $this->f044_model->aulas();
        $datos['SUCURSAL'] = $this->f044_model->sucursal();
        $dat = $this->f044_model->get_datos_generales();

        foreach ($dat as $value) {
            $datos['NOMBRE_EMPRESA'] = $value->NOMBRE_EMPRESA;
            $datos['RUC'] = $value->RUC;
            $datos['SITIO_WEB'] = $value->SITIO_WEB;
            $datos['DIRECCION'] = $value->DIRECCION;
            $datos['CALLE_NRO'] = $value->CALLE_NRO;
            $datos['PROVINCIA'] = $value->PROVINCIA;
            $datos['CIUDAD'] = $value->CIUDAD;
            $datos['TELEFONO'] = $value->TELEFONO;
            $datos['CELULAR'] = $value->CELULAR;
            $datos['CORREO'] = $value->EMAIL;
            $datos['REFERENCIA'] = $value->DIRECCION;
        }
        // Load library
        $this->load->library('dompdf_gen');
//        $this->dompdf->set_paper('A4', 'landscape');

        $this->dompdf->set_paper('A4', 'portrait');
        $this->load->view('reportes/f044', $datos);
        // Get output html
        $html = $this->output->get_output();

        // Convert to PDF
        $this->dompdf->load_html($html);
        $this->dompdf->render();

        $this->dompdf->stream("f044.pdf");
    }

    public function reporte_f007() {

        $datos['PERSONA'] = $this->f007_model->f007();

        // Load library
        $this->load->library('dompdf_gen');
        $this->dompdf->set_paper('A4', 'landscape');

//        $this->dompdf->set_paper('A4', 'portrait');
        $this->load->view('reportes/f007', $datos);
        // Get output html
        $html = $this->output->get_output();

        // Convert to PDF
        $this->dompdf->load_html($html);
        $this->dompdf->render();

        $this->dompdf->stream("f007.pdf");
    }

    public function reporte_f023() {

        // Load library
        $this->load->library('dompdf_gen');
//        $this->dompdf->set_paper('A4', 'landscape');

        $this->dompdf->set_paper('A4', 'portrait');
        $this->load->view('reportes/f023');
        // Get output html
        $html = $this->output->get_output();

        // Convert to PDF
        $this->dompdf->load_html($html);
        $this->dompdf->render();

        $this->dompdf->stream("f023.pdf");
    }

    public function reporte_f018($id_persona) {

        $result = $this->procesos_model->getFormulario18($id_persona);
        if (count($result)) {
            $data = $this->repositorio_model->get_respositorio($id_persona, '2');

            if (!$data) {
                $this->repositorio_model->insertar_persona($id_persona, '2');
            }

            $this->load->helper('path');
            $font_directory = './application/libraries/fpdf/font/';
            set_realpath($font_directory);
            $this->load->library('fpdf');
            define('FPDF_FONTPATH', $font_directory);

            $this->fpdf->Open();
            $this->fpdf->AddPage("L"); //L:Horizontal o P:Vertical
            $this->fpdf->SetFont('Arial', 'B', 15);

            $this->fpdf->Image('./assets/imagenes/logo.jpg', 15, 18, 30);
            $this->fpdf->Cell(280, 6, "", 0, 1, 'C'); //280 HORI - 190 VER
            $this->fpdf->SetFont('Arial', 'B', 11);
            $this->fpdf->Cell(45, 6, "", 0, 0, 'C');
            $this->fpdf->MultiCell(145, 6, utf8_decode("VERIFICACI??N DEL CUMPLIMIENTO DEL PERFIL DEL PERSONAL DE CERTIFICACI??N"), 1, 'C', 0);

            $alto = 7;
            $fuente = 9;

            $data = $this->usuarios_model->get_Archivo($id_persona, "F018");


            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(45, $alto - 2, "", 0, 0, 'C');
            $this->fpdf->Cell(45, $alto - 2, utf8_decode("C??digo:"), 1, 0, 'L', 0);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(100, $alto - 2, utf8_decode("ITCA-F018-$id_persona"), 1, 1, 'L', 0);

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(45, $alto - 2, "", 0, 0, 'C');
            $this->fpdf->Cell(45, $alto - 2, utf8_decode("Versi??n :"), 1, 0, 'L', 0);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(100, $alto - 2, utf8_decode("1.1"), 1, 1, 'L', 0);
            

                $this->fpdf->SetFont('Arial', 'B', $fuente);
                $this->fpdf->Cell(45, $alto - 2, "", 0, 0, 'C');
                $this->fpdf->Cell(45, $alto - 2, utf8_decode("Fecha de Emisi??n:"), 1, 0, 'L', 0);
                $this->fpdf->SetFont('Arial', '', $fuente);
                $hoy = date("d/m/y");
                $this->fpdf->Cell(100, $alto - 2, utf8_decode("" . $hoy), 1, 1, 'L', 0);

                $this->fpdf->SetFont('Arial', 'B', $fuente);
                $this->fpdf->Cell(45, $alto - 2, "", 0, 0, 'C');
                $this->fpdf->Cell(45, $alto - 2, utf8_decode("Fecha de Revisi??n :"), 1, 0, 'L', 0);
                $this->fpdf->SetFont('Arial', '', $fuente);
                $this->fpdf->Cell(100, $alto - 2, utf8_decode("" . $hoy), 1, 1, 'L', 0);
            
            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(45, $alto - 2, "", 0, 0, 'C');
            $this->fpdf->Cell(45, $alto - 2, utf8_decode("File :"), 1, 0, 'L', 0);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(100, $alto - 2, utf8_decode("F018"), 1, 1, 'L', 0);

            $this->fpdf->Ln(5);

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->SetFillColor(143, 164, 225);

            //DESDE AQUI
            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->SetFillColor(143, 164, 225);

            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->Cell(7 + 3 * 45 + 38 + 80, $alto, utf8_decode("DATOS DE IDENTIFICACI??N DEL CARGO"), 1, 1, 'C', true);

            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->Cell(80, $alto, "Item  Verificado", 1, 0, 'L', true);
            $this->fpdf->Cell(80, $alto, utf8_decode("Descripci??n"), 1, 0, 'L', true);
            $this->fpdf->Cell(20, $alto, "Cumple", 1, 0, 'L', true);
            $this->fpdf->Cell(80, $alto, utf8_decode("Observaci??n"), 1, 1, 'L', true);

            $this->fpdf->SetFont('Arial', '', $fuente - 1);
            $this->fpdf->SetFillColor(214, 230, 151);

            $id_persona_verificador = 0;
            $bandera = true;
            $i = 0;
            $altura = 5;
            $result = $this->procesos_model->getFormulario18Limites($id_persona, 1, 7);
            foreach ($result as $fila) {
                if ($i % 2 == 0)
                    $this->fpdf->SetFillColor(255, 255, 255);
                else
                    $this->fpdf->SetFillColor(218, 225, 247);


                $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
                $this->fpdf->Cell(80, $alto, utf8_decode($fila->ITEM_VEIFICADO), 1, 0, 'L', true);
                $this->fpdf->Cell(80, $alto, utf8_decode($fila->DESCRIPCION), 1, 0, 'L', true);
                $this->fpdf->Cell(20, $alto, utf8_decode($fila->CUMPLE), 1, 0, 'L', true);
                $this->fpdf->Cell(80, $alto, utf8_decode($fila->OBSERVACIONES), 1, 1, 'L', true);
//            $id_persona_verificador = $fila->ID_PERSONA_VERIFICADOR;
                $i++;
            }
            $this->fpdf->Ln();

            //DESDE AQUI
            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->SetFillColor(143, 164, 225);

            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->Cell(7 + 3 * 45 + 38 + 80, $alto, utf8_decode("FORMACI??N REQUERIDA"), 1, 1, 'C', true);

            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->Cell(80, $alto, "Item  Verificado", 1, 0, 'L', true);
            $this->fpdf->Cell(80, $alto, utf8_decode("Descripci??n"), 1, 0, 'L', true);
            $this->fpdf->Cell(20, $alto, "Cumple", 1, 0, 'L', true);
            $this->fpdf->Cell(80, $alto, utf8_decode("Observaci??n"), 1, 1, 'L', true);

            $this->fpdf->SetFont('Arial', '', $fuente - 1);
            $this->fpdf->SetFillColor(214, 230, 151);

            $bandera = true;
            $i = 0;
            $altura = 5;
            $result = $this->procesos_model->getFormulario18Limites($id_persona, 8, 9);
            foreach ($result as $fila) {
                if ($i % 2 == 0)
                    $this->fpdf->SetFillColor(255, 255, 255);
                else
                    $this->fpdf->SetFillColor(218, 225, 247);


                $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
                $this->fpdf->Cell(80, $alto, utf8_decode($fila->ITEM_VEIFICADO), 1, 0, 'L', true);
                $this->fpdf->Cell(80, $alto, utf8_decode($fila->DESCRIPCION), 1, 0, 'L', true);
                $this->fpdf->Cell(20, $alto, utf8_decode($fila->CUMPLE), 1, 0, 'L', true);
                $this->fpdf->Cell(80, $alto, utf8_decode($fila->OBSERVACIONES), 1, 1, 'L', true);
                $i++;
            }
            $this->fpdf->Ln();


            //DESDE AQUI
            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->SetFillColor(143, 164, 225);

            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->Cell(7 + 3 * 45 + 38 + 80, $alto, utf8_decode("EXPERIENCIA LABORAL REQUERIDA"), 1, 1, 'C', true);

            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->Cell(80, $alto, "Item Verificado", 1, 0, 'L', true);
            $this->fpdf->Cell(80, $alto, utf8_decode("Descripci??n"), 1, 0, 'L', true);
            $this->fpdf->Cell(20, $alto, "Cumple", 1, 0, 'L', true);
            $this->fpdf->Cell(80, $alto, utf8_decode("Observaci??n"), 1, 1, 'L', true);

            $this->fpdf->SetFont('Arial', '', $fuente - 1);
            $this->fpdf->SetFillColor(214, 230, 151);

            $bandera = true;
            $i = 0;
            $altura = 5;
            $result = $this->procesos_model->getFormulario18Limites($id_persona, 10, 10);
            foreach ($result as $fila) {
                if ($i % 2 == 0)
                    $this->fpdf->SetFillColor(255, 255, 255);
                else
                    $this->fpdf->SetFillColor(218, 225, 247);

                $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
                $this->fpdf->Cell(80, $alto, utf8_decode($fila->ITEM_VEIFICADO), 1, 0, 'L', true);
                $this->fpdf->Cell(80, $alto, utf8_decode($fila->DESCRIPCION), 1, 0, 'L', true);
                $this->fpdf->Cell(20, $alto, utf8_decode($fila->CUMPLE), 1, 0, 'L', true);
                $this->fpdf->Cell(80, $alto, utf8_decode($fila->OBSERVACIONES), 1, 1, 'L', true);
                $i++;
            }

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->SetFillColor(143, 164, 225);

            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->Cell(100, $alto, "Empresa y Puesto", 1, 0, 'L', true);
            $this->fpdf->Cell(30, $alto, utf8_decode("A??os Laborados"), 1, 0, 'L', true);
            $this->fpdf->Cell(130, $alto, utf8_decode("Actividades"), 1, 1, 'L', true);

            $this->fpdf->SetFont('Arial', '', $fuente - 1);
            $this->fpdf->SetFillColor(214, 230, 151);

            $bandera = true;
            $i = 0;
            $altura = 5;
            $result = $this->procesos_model->getFormulario18Limites($id_persona, 100, 2000);
            foreach ($result as $fila) {
                if ($i % 2 == 0)
                    $this->fpdf->SetFillColor(255, 255, 255);
                else
                    $this->fpdf->SetFillColor(218, 225, 247);
                $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
                $this->fpdf->Cell(100, $alto, utf8_decode($fila->ITEM_VEIFICADO), 1, 0, 'L', true);
                $this->fpdf->Cell(30, $alto, utf8_decode($fila->CUMPLE), 1, 0, 'L', true);
                $this->fpdf->Cell(130, $alto, utf8_decode($fila->OBSERVACIONES), 1, 1, 'L', true);
                $i++;
            }

            $this->fpdf->Ln();

            //DESDE AQUI
            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->SetFillColor(143, 164, 225);

            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->Cell(7 + 3 * 45 + 38 + 80, $alto, utf8_decode("CONOCIMIENTOS ESPEC??FICOS"), 1, 1, 'C', true);

            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->Cell(160, $alto, "Item Verificado", 1, 0, 'L', true);
            $this->fpdf->Cell(20, $alto, "Cumple", 1, 0, 'L', true);
            $this->fpdf->Cell(80, $alto, utf8_decode("Observaci??n"), 1, 1, 'L', true);

            $this->fpdf->SetFont('Arial', '', $fuente - 1);
            $this->fpdf->SetFillColor(214, 230, 151);

            $bandera = true;
            $i = 0;
            $altura = 5;
            $result = $this->procesos_model->getFormulario18Limites($id_persona, 11, 15);
            foreach ($result as $fila) {
                if ($i % 2 == 0)
                    $this->fpdf->SetFillColor(255, 255, 255);
                else
                    $this->fpdf->SetFillColor(218, 225, 247);
                $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
                $this->fpdf->Cell(160, $alto, utf8_decode($fila->ITEM_VEIFICADO), 1, 0, 'L', true);
                $this->fpdf->Cell(20, $alto, utf8_decode($fila->CUMPLE), 1, 0, 'L', true);
                $this->fpdf->Cell(80, $alto, utf8_decode($fila->OBSERVACIONES), 1, 1, 'L', true);
                $i++;
            }
            $this->fpdf->Ln();

            $texto = "Declaro bajo juramente, bajo las prevenciones de Ley que la informaci??n aqu?? consignada es ver??dica y de mi entera responsabilidad; por lo cual, la INSTITUTO TECNOL??GICO SUPERIOR 'JOS?? CHIRIBOGA GRIJALVA'  podr?? verificar esta informaci??n en cualquier momento, y en caso de comprobarse falsedad en la misma, podr??n iniciarse las acciones administrativas, civiles y penales que ampara la legislaci??n ecuatoriana vigente.";

            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->MultiCell(260, 6, utf8_decode($texto), 1, 'L', 0);

            //---------------------------------------------------------------
            $this->fpdf->Ln();

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->SetFillColor(143, 164, 225);
            $this->fpdf->MultiCell(145, 6, utf8_decode("PERSONAL INVOLUCRADO EN EL PROCESO DE CERTIFICACI??N"), 1, 'C', true);

            $persona = $this->procesos_model->getVerificador("Supervisor");
            $persona = $persona[0];
            $hoy = date("d/m/y");

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(10, $alto, "", 0, 0, 'C');
            $this->fpdf->Cell(45, $alto + 10, utf8_decode("Firma:"), 1, 0, 'L', 0);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(100, $alto + 10, utf8_decode(""), 1, 1, 'L', 0);

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(10, $alto, "", 0, 0, 'C');
            $this->fpdf->Cell(45, $alto, utf8_decode("NOMBRES COMPLETOS:"), 1, 0, 'L', 0);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(100, $alto, utf8_decode($persona->APELLIDO_PATERNO . ' ' . $persona->APELLIDO_MATERNO . ' ' . $persona->NOMBRES), 1, 1, 'L', 0);

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(10, $alto, "", 0, 0, 'C');
            $this->fpdf->Cell(45, $alto, utf8_decode("CARGO:"), 1, 0, 'L', 0);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(100, $alto, utf8_decode($persona->CARGO), 1, 1, 'L', 0);

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(10, $alto, "", 0, 0, 'C');
            $this->fpdf->Cell(45, $alto, utf8_decode("No. DE TEL??FONO:"), 1, 0, 'L', 0);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(100, $alto, utf8_decode($persona->NUMERO_TELEFONO), 1, 1, 'L', 0);

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(10, $alto, "", 0, 0, 'C');
            $this->fpdf->Cell(45, $alto, utf8_decode("CORREO ELECTR??NICO:"), 1, 0, 'L', 0);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(100, $alto, utf8_decode($persona->E_MAIL), 1, 1, 'L', 0);

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(10, $alto, "", 0, 0, 'C');
            $this->fpdf->Cell(45, $alto, utf8_decode("FECHA:"), 1, 0, 'L', 0);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(100, $alto, utf8_decode($hoy), 1, 1, 'L', 0);

            $this->fpdf->Ln();

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->MultiCell(145, 6, utf8_decode("VERIFICADO POR"), 1, 'C', TRUE);

            $persona = $this->procesos_model->getVerificador("Analista de certificaci??n y control");
            $persona = $persona[0];

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(10, $alto, "", 0, 0, 'C');
            $this->fpdf->Cell(45, $alto + 10, utf8_decode("Firma:"), 1, 0, 'L', 0);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(100, $alto + 10, utf8_decode(""), 1, 1, 'L', 0);

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(10, $alto, "", 0, 0, 'C');
            $this->fpdf->Cell(45, $alto, utf8_decode("NOMBRES COMPLETOS:"), 1, 0, 'L', 0);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(100, $alto, utf8_decode($persona->APELLIDO_PATERNO . ' ' . $persona->APELLIDO_MATERNO . ' ' . $persona->NOMBRES), 1, 1, 'L', 0);

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(10, $alto, "", 0, 0, 'C');
            $this->fpdf->Cell(45, $alto, utf8_decode("CARGO:"), 1, 0, 'L', 0);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(100, $alto, utf8_decode($persona->CARGO), 1, 1, 'L', 0);

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(10, $alto, "", 0, 0, 'C');
            $this->fpdf->Cell(45, $alto, utf8_decode("No. DE TEL??FONO:"), 1, 0, 'L', 0);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(100, $alto, utf8_decode($persona->NUMERO_TELEFONO), 1, 1, 'L', 0);

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(10, $alto, "", 0, 0, 'C');
            $this->fpdf->Cell(45, $alto, utf8_decode("CORREO ELECTR??NICO:"), 1, 0, 'L', 0);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(100, $alto, utf8_decode($persona->E_MAIL), 1, 1, 'L', 0);

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(10, $alto, "", 0, 0, 'C');
            $this->fpdf->Cell(45, $alto, utf8_decode("FECHA:"), 1, 0, 'L', 0);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(100, $alto, utf8_decode($hoy), 1, 1, 'L', 0);

            $this->fpdf->Ln();

            //DESDE AQUI
            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->SetFillColor(143, 164, 225);

            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->Cell(7 + 3 * 45 + 38 + 80, $alto, utf8_decode("CONTROL DE EMISI??N"), 1, 1, 'C', true);

            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->Cell(50, $alto, "", 1, 0, 'L', false);
            $this->fpdf->Cell(70, $alto, utf8_decode("Elabor??"), 1, 0, 'L', false);
            $this->fpdf->Cell(70, $alto, utf8_decode("Revis??"), 1, 0, 'L', false);
            $this->fpdf->Cell(70, $alto, utf8_decode("Autoriz??"), 1, 1, 'L', false);

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->Cell(50, $alto, "Nombre", 1, 0, 'L', false);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $persona = $this->procesos_model->getVerificador("Analista de certificaci??n y control");
            $persona = $persona[0];
            $this->fpdf->Cell(70, $alto, utf8_decode("Ing. $persona->APELLIDO_PATERNO $persona->APELLIDO_MATERNO $persona->NOMBRES"), 1, 0, 'L', false);
            $persona = $this->procesos_model->getVerificador("Responsable de Proceso de Certificaci??n");
            $persona = $persona[0];
            $this->fpdf->Cell(70, $alto, utf8_decode("Dra. $persona->APELLIDO_PATERNO $persona->APELLIDO_MATERNO $persona->NOMBRES"), 1, 0, 'L', false);
            $persona = $this->procesos_model->getVerificador("Coordinadora del Comit?? de Certificaci??n");
            $persona = $persona[0];
            $this->fpdf->Cell(70, $alto, utf8_decode("Dra. $persona->APELLIDO_PATERNO $persona->APELLIDO_MATERNO $persona->NOMBRES"), 1, 1, 'L', false);

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->Cell(50, $alto, "Cargo", 1, 0, 'L', false);
            $this->fpdf->SetFont('Arial', '', $fuente - 1);
            $this->fpdf->Cell(70, $alto, utf8_decode("Analista de Certificaci??n y Control"), 1, 0, 'L', false);
            $this->fpdf->Cell(70, $alto, utf8_decode("Responsable de procesos de certificaci??n y control"), 1, 0, 'L', false);
            $this->fpdf->Cell(70, $alto, utf8_decode("Coordinador del Comit?? de Certificaci??n"), 1, 1, 'L', false);

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(10, $alto + 10, "", 0, 0, 'L');
            $this->fpdf->Cell(50, $alto + 10, "Firma", 1, 0, 'L', false);
            $this->fpdf->SetFont('Arial', '', $fuente - 1);
            $this->fpdf->Cell(70, $alto + 10, "", 1, 0, 'L', false);
            $this->fpdf->Cell(70, $alto + 10, "", 1, 0, 'L', false);
            $this->fpdf->Cell(70, $alto + 10, "", 1, 1, 'L', false);

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(10, $alto + 10, "", 0, 0, 'L');
            $this->fpdf->Cell(50, $alto + 10, "fecha", 1, 0, 'L', false);
            $this->fpdf->SetFont('Arial', '', $fuente - 1);
            $this->fpdf->Cell(70, $alto + 10, "", 1, 0, 'L', false);
            $this->fpdf->Cell(70, $alto + 10, "", 1, 0, 'L', false);
            $this->fpdf->Cell(70, $alto + 10, "", 1, 1, 'L', false);

            $this->fpdf->Ln();

            $this->fpdf->Output();
        }else {
            echo "No existen datos";
        }
    }

    public function reporte_f015($id_evaluacion_examinador) {

        $result = $this->evaluacion_examinadores_model->getPersonasExaminadores($id_evaluacion_examinador);
        if (count($result)) {

            $data = $this->repositorio_model->get_respositorio($id_evaluacion_examinador, '3');

            if (!$data) {
                $this->repositorio_model->insertar_persona($id_evaluacion_examinador, '3');
            }
            $result = $result[0];
            $this->load->helper('path');
            $font_directory = './application/libraries/fpdf/font/';
            set_realpath($font_directory);
            $this->load->library('fpdf');
            define('FPDF_FONTPATH', $font_directory);

            $this->fpdf->Open();
            $this->fpdf->AddPage("P"); //L:Horizontal o P:Vertical
            $this->fpdf->SetFont('Arial', 'B', 15);

            $this->fpdf->Image('./assets/imagenes/logo.jpg', 15, 18, 30);
            $this->fpdf->Cell(190, 6, "", 0, 1, 'C'); //280 HORI - 190 VER
            $this->fpdf->SetFont('Arial', 'B', 11);
            $this->fpdf->Cell(45, 6, "", 0, 0, 'C');
            $this->fpdf->MultiCell(145, 6, utf8_decode("EVALUACI??N DE CONOCIMIENTO AL EXAMINADOR SOBRE EL PROCESO DE CERTIFICACI??N"), 1, 'C', 0);

            $alto = 7;
            $fuente = 9;

            $data = $this->usuarios_model->get_Archivo($result->ID_PERSONA, "F018");


            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(45, $alto - 2, "", 0, 0, 'C');
            $this->fpdf->Cell(45, $alto - 2, utf8_decode("C??digo:"), 1, 0, 'L', 0);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(100, $alto - 2, utf8_decode("ITCA-F015-$result->ID_PERSONA"), 1, 1, 'L', 0);

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(45, $alto - 2, "", 0, 0, 'C');
            $this->fpdf->Cell(45, $alto - 2, utf8_decode("Versi??n :"), 1, 0, 'L', 0);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(100, $alto - 2, utf8_decode("1.1"), 1, 1, 'L', 0);


            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(45, $alto - 2, "", 0, 0, 'C');
            $this->fpdf->Cell(45, $alto - 2, utf8_decode("Fecha de Emisi??n:"), 1, 0, 'L', 0);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $hoy = date("d/m/y");
            $this->fpdf->Cell(100, $alto - 2, utf8_decode("" . $hoy), 1, 1, 'L', 0);

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(45, $alto - 2, "", 0, 0, 'C');
            $this->fpdf->Cell(45, $alto - 2, utf8_decode("Fecha de Revisi??n :"), 1, 0, 'L', 0);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(100, $alto - 2, utf8_decode("" . $hoy), 1, 1, 'L', 0);


            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(45, $alto - 2, "", 0, 0, 'C');
            $this->fpdf->Cell(45, $alto - 2, utf8_decode("File :"), 1, 0, 'L', 0);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(100, $alto - 2, utf8_decode("F015"), 1, 1, 'L', 0);

            $this->fpdf->Ln(15);
//
//            //DESDE AQUI

            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(60, $alto, "Nombres del Candidato a Examinador:", 0, 0, 'L', false);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(120, $alto, utf8_decode($result->APELLIDO_PATERNO . ' ' . $result->APELLIDO_MATERNO . ' ' . $result->NOMBRES), 0, 1, 'L', false);

            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(60, $alto, utf8_decode("Fecha de evaluaci??n:"), 0, 0, 'L', false);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(120, $alto, utf8_decode($result->FECHA_EVALUACION), 0, 1, 'L', false);

            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(60, $alto, utf8_decode("Perfil calificado para ser examinador:"), 0, 1, 'L', false);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->MultiCell(180, 6, utf8_decode($result->NOMBRE_PERFIL_PRO_ESTADO), 0, 'L', 0);

            $this->fpdf->Ln();
            $this->fpdf->SetFont('Arial', 'B', 10);
            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->SetFillColor(214, 230, 151);
            $this->fpdf->Cell(180, $alto, utf8_decode("A. Esquema de Certificaci??n (50%)"), 1, 1, 'L', true);


            $esquemas = $this->evaluacion_examinadores_model->getRespuestasEsquemas2($id_evaluacion_examinador);
            $suma_esquemas = 0;
            $limite_esquemas = 0;
            foreach ($esquemas as $value) {
                $this->fpdf->Ln();
                $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
                $this->fpdf->SetFont('Arial', 'B', $fuente);
                $this->fpdf->Cell(20, $alto, "- Pregunta:", 0, 0, 'L');
                $this->fpdf->SetFont('Arial', '', $fuente);
                $resp = strip_tags($value->DESCRIPCION_PREGUNTA);

                $healthy = array("&aacute;", "&eacute;", "&iacute;", "&oacute;", "&uacute;", "&nbsp;", "&ntilde;");
                $yummy = array("??", "??", "??", "??", "??", "", "??");
                $resp = str_replace($healthy, $yummy, $resp);

                $this->fpdf->MultiCell(160, 5, utf8_decode($resp), 1, 'J', 0);

                $this->fpdf->Cell(30, $alto, "", 0, 0, 'L');
                $this->fpdf->SetFont('Arial', '', $fuente);
                $this->fpdf->Cell(20, $alto, utf8_decode("Puntaje: " . $value->CALIFICACION), 0, 1, 'L');

                $suma_esquemas = $suma_esquemas + $value->CALIFICACION;
                $limite_esquemas = $limite_esquemas + $value->VALOR_MAXIMO;
            }

            $this->fpdf->Ln();
            $this->fpdf->Ln();
            $this->fpdf->SetFont('Arial', 'B', 10);
            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->SetFillColor(214, 230, 151);
            $this->fpdf->Cell(180, $alto, utf8_decode("B. Procedimientos de los ex??menes y documentos (50%)"), 1, 1, 'L', true);

            $procedimientos = $this->evaluacion_examinadores_model->getRespuestasProcedimientos2($id_evaluacion_examinador);

            $suma_procedimientos = 0;
            $limite_procedimientos = 0;
            foreach ($procedimientos as $value) {
                $this->fpdf->Ln();
                $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
                $this->fpdf->SetFont('Arial', 'B', $fuente);
                $this->fpdf->Cell(22, $alto, "- Pregunta:", 0, 0, 'L');
                $this->fpdf->SetFont('Arial', '', $fuente);
                $resp = strip_tags($value->DESCRIPCION_PREGUNTA);

                $healthy = array("&aacute;", "&eacute;", "&iacute;", "&oacute;", "&uacute;", "&nbsp;", "&ntilde;");
                $yummy = array("??", "??", "??", "??", "??", "", "??");
                $resp = str_replace($healthy, $yummy, $resp);
                $this->fpdf->MultiCell(160, 5, utf8_decode($resp), 1, 'J', 0);

                $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
                $this->fpdf->SetFont('Arial', 'B', $fuente);
                $this->fpdf->Cell(22, $alto, "- Respuesta:", 0, 0, 'L');
                $this->fpdf->SetFont('Arial', '', $fuente);
                $resp = strip_tags($value->RESPUESTA);

                $healthy = array("&aacute;", "&eacute;", "&iacute;", "&oacute;", "&uacute;", "&nbsp;", "&ntilde;");
                $yummy = array("??", "??", "??", "??", "??", "", "??");
                $resp = str_replace($healthy, $yummy, $resp);
                $this->fpdf->MultiCell(160, 5, utf8_decode($resp), 1, 'J', 0);

                $this->fpdf->Cell(30, $alto, "", 0, 0, 'L');
                $this->fpdf->SetFont('Arial', '', $fuente);
                $this->fpdf->Cell(20, $alto, utf8_decode("Puntaje: " . $value->VALOR_RESPUESTA), 0, 1, 'L');

                $suma_procedimientos = $suma_procedimientos + $value->VALOR_RESPUESTA;
                $limite_procedimientos = $limite_procedimientos + $value->VALOR_MAXIMO;
            }
            $this->fpdf->Ln();
            $this->fpdf->Ln();
            $this->fpdf->Ln();

            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->Cell(50, $alto, utf8_decode("Firma del Candidato/a Examinador/a"), 0, 1, 'L');

            $this->fpdf->Ln();
            $this->fpdf->Ln();

            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->Cell(50, $alto, utf8_decode("Firma de la persona que aplic?? la examinaci??n"), 0, 1, 'L');

            $this->fpdf->Ln();
            $this->fpdf->Ln();

            $total_esquemas = 50 * $suma_esquemas / $limite_esquemas;

            $total_procedimientos = 50 * $suma_procedimientos / $limite_procedimientos;

            $total = $total_esquemas + $total_procedimientos;
            if ($total >= 70) {
                $this->evaluacion_model->actulizarEstado1($result->ID_PERSONA, '3');
            }

            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(10, $alto - 2, "", 0, 0, 'L');
            $this->fpdf->Cell(50, $alto - 2, utf8_decode("CALIFICACI??N Obtenida por el candidato a examinador: $total"), 0, 1, 'L');
            $this->fpdf->Cell(10, $alto - 2, "", 0, 0, 'L');
            $this->fpdf->Cell(50, $alto - 2, utf8_decode("Calificaci??n m??nima 70 / 100 % del total de preguntas evaluadas."), 0, 1, 'L');

            $this->fpdf->Ln();
            $this->fpdf->Ln();


            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->Cell(50, $alto, "Nombre", 1, 0, 'L', false);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $persona = $this->procesos_model->getVerificador("Coordinadora del Comit?? de Certificaci??n");
            $persona = $persona[0];
            $this->fpdf->Cell(70, $alto, utf8_decode("Dra. $persona->APELLIDO_PATERNO $persona->APELLIDO_MATERNO $persona->NOMBRES"), 1, 0, 'L', false);
            $persona = $this->procesos_model->getVerificador("Responsable de Proceso de Certificaci??n");
            $persona = $persona[0];
            $this->fpdf->Cell(60, $alto, utf8_decode("Dra. $persona->APELLIDO_PATERNO $persona->APELLIDO_MATERNO $persona->NOMBRES"), 1, 1, 'L', false);

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->Cell(50, $alto, "Cargo", 1, 0, 'L', false);
            $this->fpdf->SetFont('Arial', '', $fuente - 1);
            $this->fpdf->Cell(70, $alto, utf8_decode("Coordinadora del Comit?? de Certificaci??n"), 1, 0, 'L', false);
            $this->fpdf->Cell(60, $alto, utf8_decode("Responsable de Proceso de Certificaci??n"), 1, 1, 'L', false);

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(10, $alto + 10, "", 0, 0, 'L');
            $this->fpdf->Cell(50, $alto + 10, "Firma", 1, 0, 'L', false);
            $this->fpdf->SetFont('Arial', '', $fuente - 1);
            $this->fpdf->Cell(70, $alto + 10, "", 1, 0, 'L', false);
            $this->fpdf->Cell(60, $alto + 10, "", 1, 1, 'L', false);

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(10, $alto + 10, "", 0, 0, 'L');
            $this->fpdf->Cell(50, $alto + 10, "fecha", 1, 0, 'L', false);
            $this->fpdf->SetFont('Arial', '', $fuente - 1);
            $this->fpdf->Cell(70, $alto + 10, "", 1, 0, 'L', false);
            $this->fpdf->Cell(60, $alto + 10, "", 1, 1, 'L', false);

            $this->fpdf->Ln();

            $this->fpdf->Output();
        } else {
            echo "No existen datos";
        }
    }

    public function reporte_f009($id_persona) {

        $result = $this->evaluacion_examinadores_model->getPersonasF009($id_persona);
        if (count($result)) {
            
            $dat = $this->repositorio_model->get_respositorio($id_persona, '9');

        if (!$dat) {
            $this->repositorio_model->insertar_persona($id_persona, '9');
        }
        
        
            $data = $this->repositorio_model->get_respositorio($id_persona, '4');

            if (!$data) {
                $this->repositorio_model->insertar_persona($id_persona, '4');
            }
            $persona = $result[0];
            $this->load->helper('path');
            $font_directory = './application/libraries/fpdf/font/';
            set_realpath($font_directory);
            $this->load->library('fpdf');
            define('FPDF_FONTPATH', $font_directory);

            $this->fpdf->Open();
            $this->fpdf->AddPage("P"); //L:Horizontal o P:Vertical
            $this->fpdf->SetFont('Arial', 'B', 15);

            $this->fpdf->Image('./assets/imagenes/logo.jpg', 15, 18, 30);
            $this->fpdf->Cell(190, 6, "", 0, 1, 'C'); //280 HORI - 190 VER
            $this->fpdf->SetFont('Arial', 'B', 11);
            $this->fpdf->Cell(45, 6, "", 0, 0, 'C');
            $this->fpdf->MultiCell(145, 6, utf8_decode("CERTIFICADO DE INDUCCI??N DEL PROCESO DE CERTIFICACI??N DE PERSONAS"), 1, 'C', 0);

            $alto = 7;
            $fuente = 9;

            $data = $this->usuarios_model->get_Archivo($id_persona, "F018");

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(45, $alto - 2, "", 0, 0, 'C');
            $this->fpdf->Cell(45, $alto - 2, utf8_decode("C??digo:"), 1, 0, 'L', 0);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(100, $alto - 2, utf8_decode("ITCA-F009-$id_persona"), 1, 1, 'L', 0);

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(45, $alto - 2, "", 0, 0, 'C');
            $this->fpdf->Cell(45, $alto - 2, utf8_decode("Versi??n :"), 1, 0, 'L', 0);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(100, $alto - 2, utf8_decode("1.1"), 1, 1, 'L', 0);

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(45, $alto - 2, "", 0, 0, 'C');
            $this->fpdf->Cell(45, $alto - 2, utf8_decode("Fecha de Emisi??n:"), 1, 0, 'L', 0);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $hoy = date("d/m/y");
            $this->fpdf->Cell(100, $alto - 2, utf8_decode("" . $hoy), 1, 1, 'L', 0);

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(45, $alto - 2, "", 0, 0, 'C');
            $this->fpdf->Cell(45, $alto - 2, utf8_decode("Fecha de Revisi??n :"), 1, 0, 'L', 0);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(100, $alto - 2, utf8_decode("" . $hoy), 1, 1, 'L', 0);

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(45, $alto - 2, "", 0, 0, 'C');
            $this->fpdf->Cell(45, $alto - 2, utf8_decode("File :"), 1, 0, 'L', 0);
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->Cell(100, $alto - 2, utf8_decode("F009"), 1, 1, 'L', 0);

            $this->fpdf->Ln(12);

            $this->fpdf->SetFont('Arial', 'B', $fuente);

            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->Cell(180, $alto, utf8_decode("CERTIFICACI??N No. 00" . $id_persona), 0, 1, 'C', false);

            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->SetFont('Arial', '', $fuente);
            $nombres = $persona->APELLIDO_PATERNO . ' ' . $persona->APELLIDO_MATERNO . ' ' . $persona->NOMBRES;
            $mensaje = "Por medio del presente, certifico que el(la) Sr. (Sra.) $nombres, con C??dula de Ciudadan??a N?? " . $persona->CEDULA . ", ha participado en el proceso de Inducci??n del  Sistema de Gesti??n de Certificaci??n de Personas por Competencias Laborales,  realizado el d??a  ___ de ____  del 201_, con una duraci??n de  ____ horas.";
            $this->fpdf->MultiCell(180, 6, utf8_decode($mensaje), 0, 'J', 0);

            $this->fpdf->Ln();

            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->SetFont('Arial', '', $fuente);
            $mensaje = "Esta inducci??n es habilitante para participar como (personal involucrado en el proceso de certificaci??n) en la ejecuci??n de las actividades de los Procesos del Sistema de Gesti??n de Certificaci??n de Personas del Instituto Tecnol??gico Superior \"Jos?? Chiriboga Grijalva\" en relaci??n al desarrollo del proceso de Certificaci??n de Personas por Competencias Laborales.";
            $this->fpdf->MultiCell(180, 6, utf8_decode($mensaje), 0, 'J', 0);

            $this->fpdf->Ln();

            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->SetFont('Arial', '', $fuente);
            $mensaje = "Los temas tratados fueron:";
            $this->fpdf->MultiCell(180, 6, utf8_decode($mensaje), 0, 'J', 0);


            $temas = array();
            $temas[] = "Aceptaci??n de la aplicaci??n para la certificaci??n";
            $temas[] = "Acuerdo de cumplimiento con los lineamientos para personas certificadas";
            $temas[] = "Aplicaci??n para la certificaci??n de personas";
            $temas[] = "Certificado de competencia laboral";
            $temas[] = "C??digo de ??tica y conducta para el examinado";
            $temas[] = "Declaraci??n de imparcialidad en la examinaci??n de competencia";
            $temas[] = "Diagrama de flujo del proceso de certificaci??n";
            $temas[] = "Evaluaci??n diagn??stica de lecto-escritura y c??lculo b??sico";
            $temas[] = "Examinaci??n de la competencia";
            $temas[] = "Gu??a para la examinaci??n de la competencia";
            $temas[] = "Notificaci??n de resultados de la re-examinaci??n";
            $temas[] = "Lista de asistencia a la examinaci??n";
            $temas[] = "Lista de verificaci??n del lugar de examinaci??n";
            $temas[] = "Lista de verificaci??n del proceso de examinaci??n";
            $temas[] = "Lista maestra de documentos";
            $temas[] = "No aceptaci??n de aplicaci??n para la certificaci??n";
            $temas[] = "Notificaci??n de certificaci??n - No certificaci??n";
            $temas[] = "Notificaci??n de resultados de la examinaci??n";
            $temas[] = "Notificaci??n de suspensi??n o retiro de la certificaci??n";
            $temas[] = "Procedimiento de concesi??n y mantenimiento de la certificaci??n";
            $temas[] = "Procedimiento de examinaci??n de la competencia";
            $temas[] = "Procedimiento de renovaci??n, suspensi??n, modificaci??n del alcance o retiro de la certificaci??n";
            $temas[] = "Recomendaci??n para certificaci??n por competencias laborales";
            $temas[] = "Recomendaci??n para no certificaci??n";
            $temas[] = "Registro de ausencia e informe de cambio de fecha de la examinaci??n";
            $temas[] = "Solicitud  de cambio de fecha de examinaci??n";
            $temas[] = "Acuerdo de no subcontrataci??n";
            $temas[] = "Notificaci??n de aceptaci??n de la aplicaci??n para la certificaci??n";
            $temas[] = "Solicitud de ampliaci??n de alcance de certificaci??n";

            $temas[] = "Documentos relacionados con el funcionamiento del OEC";
            $temas[] = "Acta de constituci??n del Comit?? de Certificaci??n del OEC";
            $temas[] = "Acuerdo de confidencialidad y responsabilidad de  manejo y acceso a los ex??menes";
            $temas[] = "Acuerdo de confidencialidad y responsabilidad de la informaci??n.";
            $temas[] = "An??lisis modal de fallos y efectos";
            $temas[] = "Base de datos de aspirantes, candidatos y examinados";
            $temas[] = "Base de datos de traductores";
            $temas[] = "Base de datos personal interno del OEC";
            $temas[] = "Base de examinadores calificados";
            $temas[] = "Certificado de inducci??n del proceso de certificaci??n de personas";
            $temas[] = "C??digo de ??tica y conducta para el personal involucrado en el proceso de certificaci??n de personas";
            $temas[] = "Cuadro de evaluaci??n de examinadores";
            $temas[] = "Diagrama de flujo de la gesti??n de solicitudes";
            $temas[] = "Descripci??n de funciones del personal que integra el comit?? de certificaci??n del OEC";
            $temas[] = "Encuesta de satisfacci??n del examinado";
            $temas[] = "Evaluaci??n de conocimiento al examinador sobre el proceso de certificaci??n";
            $temas[] = "Evaluaci??n de desempe??o del examinador";
            $temas[] = "Formulario para quejas y apelaciones en relaci??n a los servicios de certificaci??n de personas";
            $temas[] = "Formulario verificaci??n del cumplimiento del perfil del personal de certificaci??n";
            $temas[] = "Formulario verificaci??n del expediente del personal involucrado";
            $temas[] = "Formulario verificaci??n trimestral del personal involucrado";
            $temas[] = "Hoja de evaluaci??n al examinador para selecci??n";
            $temas[] = "Lista de control del expediente del examinador";
            $temas[] = "Perfil del personal de certificaci??n";
            $temas[] = "Procedimiento de control de documentos";
            $temas[] = "Procedimiento de control de registros";
            $temas[] = "Procedimiento de resoluci??n de quejas y apelaciones";
            $temas[] = "Niveles de competencia para examinados - Cat??logo Nacional de Cualificaciones";
            $temas[] = "Procedimiento gesti??n de solicitudes";
            $temas[] = "Procedimiento interno del OEC para certificaci??n de personas";
            $temas[] = "Procedimiento manejo de imparcialidad y confidencialidad";
            $temas[] = "Procedimiento para evaluaci??n de desempe??o y seguimiento a examinadores";
            $temas[] = "Procedimiento para la Selecci??n de examinadores";
            $temas[] = "Programa de auditor??as internas - a??o";
            $temas[] = "Registro de acciones preventivas, correctivas y seguimiento de hallazgos";
            $temas[] = "Hoja de vida para el personal del OEC";
            $temas[] = "Resoluci??n No. SE-01-003-2016 Norma T??cnica de Reconocimiento de Organismos Evaluadores de Conformidad para certificaci??n de personas";
            $temas[] = "Responsabilidad y normas de uso de la clave de acceso a ex??menes digitales";
            $temas[] = "Reuni??n de revisi??n por el Coordinador";
            $temas[] = "Verificaci??n de competencia de examinadores";
            $temas[] = "Procedimiento de Control de No Conformidades, acciones correctivas y preventivas";
            $temas[] = "Registro de hallazgos";
            $temas[] = "Aplicaci??n para examinadores";
            $temas[] = "Manual del sistema de gesti??n de la certificaci??n de personas del Nombre del OEC";
            $temas[] = "Solicitud aplicaci??n para OEC";

            $indice = 0;
            foreach ($temas as $value) {
                $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
                $this->fpdf->SetFont('Arial', '', $fuente);
                $item = $indice + 1;
                $this->fpdf->MultiCell(180, 6, utf8_decode("" . $item . ". " . $temas[$indice]), 1, 'J', 0);
                $indice++;
            }

            $this->fpdf->Ln(30);

            $alto = 5;

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->SetFont('Arial', '', $fuente);
            $persona2 = $this->procesos_model->getVerificador("Analista de Certificaci??n y control");
            $persona2 = $persona2[0];
            $this->fpdf->Cell(90, $alto, utf8_decode("Ing. $persona2->APELLIDO_PATERNO $persona2->APELLIDO_MATERNO $persona2->NOMBRES"), 0, 0, 'C', false);
            $persona2 = $this->procesos_model->getVerificador("Responsable de Proceso de Certificaci??n");
            $persona2 = $persona2[0];
            $this->fpdf->Cell(90, $alto, utf8_decode("Dra. $persona2->APELLIDO_PATERNO $persona2->APELLIDO_MATERNO $persona2->NOMBRES"), 0, 1, 'C', false);

            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->SetFont('Arial', '', $fuente - 1);
            $this->fpdf->Cell(90, $alto, utf8_decode("Analista de Certificaci??n y control"), 0, 0, 'C', false);
            $this->fpdf->Cell(90, $alto, utf8_decode("Responsable de Proceso de Certificaci??n"), 0, 1, 'C', false);

            $this->fpdf->AddPage("P"); //L:Horizontal o P:Vertical
            $this->fpdf->SetFont('Arial', 'B', 15);


            $this->fpdf->Ln(30);

            $this->fpdf->SetFont('Arial', 'B', $fuente);

            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->Cell(180, $alto, utf8_decode("COMPROMISO"), 0, 1, 'C', false);
            $this->fpdf->Ln(15);

            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->SetFont('Arial', '', $fuente);
            $nombres = $persona->APELLIDO_PATERNO . ' ' . $persona->APELLIDO_MATERNO . ' ' . $persona->NOMBRES;
            $mensaje = "Yo, $nombres en el marco de  los procesos del Sistema de Gesti??n de Certificaci??n de Personas me comprometo a cumplir estrictamente los procesos conforme las responsabilidades asignadas por el Instituto Tecnol??gico Superior \"Jos?? Chiriboga Grijalva\", para lo cual me comprometo a dominar la normativa vigente aplicable.";
            $this->fpdf->MultiCell(180, 10, utf8_decode($mensaje), 0, 'J', 0);

            $this->fpdf->Ln(15);

            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->SetFont('Arial', '', $fuente);
            $mensaje = "$nombres";
            $this->fpdf->MultiCell(180, 6, utf8_decode($mensaje), 0, 'J', 0);

            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->MultiCell(180, 6, utf8_decode($persona->CEDULA), 0, 'J', 0);

            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->MultiCell(180, 6, utf8_decode($persona->CARGO), 0, 'J', 0);

            $this->fpdf->Ln(10);

            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->SetFont('Arial', 'B', $fuente);
            $this->fpdf->MultiCell(180, 6, utf8_decode("RECIBIDO"), 0, 'J', 0);

            $persona2 = $this->procesos_model->getVerificador("Analista de Certificaci??n y control");
            $persona2 = $persona2[0];

            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->MultiCell(180, 6, utf8_decode("Recibido por Analista de Certificaci??n y Control: Ing. $persona2->APELLIDO_PATERNO $persona2->APELLIDO_MATERNO $persona2->NOMBRES"), 0, 'J', 0);

            $this->fpdf->Ln(10);

            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->SetFont('Arial', '', $fuente);
            $hoy = date("d/m/y");
            $this->fpdf->MultiCell(180, 6, utf8_decode("Fecha: $hoy"), 0, 'J', 0);

            $this->fpdf->Ln(10);

            $this->fpdf->Cell(10, $alto, "", 0, 0, 'L');
            $this->fpdf->SetFont('Arial', '', $fuente);
            $this->fpdf->MultiCell(180, 6, utf8_decode("Firma: _______________"), 0, 'J', 0);


            $this->fpdf->Output();
        } else {
            echo "No existen datos";
        }
    }

}
