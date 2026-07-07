<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DemoLandingSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // ── 1. Insertar empresas de prueba ──────────────────────────────────
        $companies = [
            [
                'name'        => 'TechPeru Solutions',
                'ruc'         => '20512345601',
                'email'       => 'rrhh@techperu.pe',
                'phone'       => '01-456-7890',
                'description' => 'Empresa líder en desarrollo de software e innovación tecnológica en Perú. Contamos con más de 200 colaboradores.',
                'website'     => 'https://techperu.pe',
                'address'     => 'Av. Javier Prado Este 4200, San Isidro',
                'logo'        => null,
                'is_verified' => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Grupo Andino Comercial',
                'ruc'         => '20512345602',
                'email'       => 'talentos@grupoandino.com.pe',
                'phone'       => '01-234-5678',
                'description' => 'Conglomerado comercial con presencia en retail, logística y distribución a nivel nacional.',
                'website'     => 'https://grupoandino.com.pe',
                'address'     => 'Av. La Marina 2000, San Miguel',
                'logo'        => null,
                'is_verified' => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Innova Marketing Digital',
                'ruc'         => '20512345603',
                'email'       => 'empleo@innova.pe',
                'phone'       => '01-789-1234',
                'description' => 'Agencia de marketing digital especializada en estrategias SEO, SEM y redes sociales para empresas latinoamericanas.',
                'website'     => 'https://innova.pe',
                'address'     => 'Calle Las Camelias 790, San Isidro',
                'logo'        => null,
                'is_verified' => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'FinanzasPro Consulting',
                'ruc'         => '20512345604',
                'email'       => 'capital.humano@finanzaspro.pe',
                'phone'       => '01-321-6549',
                'description' => 'Consultora financiera con más de 15 años brindando soluciones contables, tributarias y de auditoría.',
                'website'     => 'https://finanzaspro.pe',
                'address'     => 'Jr. Lampa 801, Cercado de Lima',
                'logo'        => null,
                'is_verified' => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Salud Total Clínicas',
                'ruc'         => '20512345605',
                'email'       => 'rrhh@saludtotal.pe',
                'phone'       => '01-654-9870',
                'description' => 'Red de clínicas y centros médicos con cobertura en Lima, Arequipa y Cusco.',
                'website'     => 'https://saludtotal.pe',
                'address'     => 'Av. Salaverry 1801, Jesús María',
                'logo'        => null,
                'is_verified' => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ];

        $companyIds = [];
        foreach ($companies as $company) {
            $id = DB::table('job_opportunity_company')->insertGetId($company);
            $companyIds[] = $id;
        }

        // También incluir la empresa que ya existe (id 592)
        $companyIds[] = 592;

        // ── 2. Estado activo = id 2 (key='active') ─────────────────────────
        $activeStateId = 2;

        // IDs de lookup existentes:
        // Categorías: 1=Informatica/Tecnologia, 2=Marketing, 3=Administrativo, 4=Tecnologia
        // Localización: 1=Remoto, 2=Presencial, 3=Híbrido
        // Jornada: 1=Jornada Completa, 2=Becas/Prácticas, 3=Jornada Parcial, 4=Por Horas
        // Contrato: 1=Indeterminado, 2=Plazo fijo, 3=Por temporada, 4=Largo plazo

        $offers = [
            // TechPeru Solutions
            [
                'title'            => 'Desarrollador Backend Laravel',
                'slug'             => 'desarrollador-backend-laravel-' . time() . '1',
                'description'      => "Buscamos un desarrollador backend con experiencia sólida en Laravel para unirse a nuestro equipo de producto.\n\nSerás responsable de diseñar e implementar APIs RESTful, optimizar consultas a base de datos y colaborar con el equipo frontend.",
                'requirements'     => "- Mínimo 2 años de experiencia con Laravel\n- Conocimiento de MySQL y Redis\n- Experiencia con Git y metodologías ágiles\n- Inglés técnico (lectura)",
                'benefits'         => "- Trabajo remoto 100%\n- Seguro médico privado\n- Capacitaciones pagadas\n- 14 sueldos al año",
                'salary'           => 4500.00,
                'salary_currency'  => 'SOLES',
                'publication_date' => $now->copy()->subDays(1),
                'deadline'         => $now->copy()->addDays(30),
                'address'          => 'Av. Javier Prado Este 4200',
                'department'       => 'Lima',
                'province'         => 'Lima',
                'country'          => 'Perú',
                'company_id'       => $companyIds[0],
                'location_id'      => 1, // Remoto
                'state_id'         => $activeStateId,
                'category_id'      => 1, // Informatica
                'work_schedule_id' => 1, // Jornada Completa
                'contract_type_id' => 1, // Indeterminado
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'title'            => 'Frontend Developer React',
                'slug'             => 'frontend-developer-react-' . time() . '2',
                'description'      => "Únete a nuestro equipo como Desarrollador Frontend. Trabajarás en aplicaciones modernas usando React y TypeScript.\n\nColaborarás estrechamente con diseñadores UX y el equipo de backend para crear interfaces de usuario excepcionales.",
                'requirements'     => "- Experiencia con React y TypeScript\n- Conocimiento de CSS moderno (Tailwind, CSS Modules)\n- Familiaridad con Vite o Webpack\n- Buenas prácticas de accesibilidad web",
                'benefits'         => "- Horario flexible\n- Laptop de última generación\n- Stock options\n- Ambiente startup dinámico",
                'salary'           => 3800.00,
                'salary_currency'  => 'SOLES',
                'publication_date' => $now->copy()->subDays(2),
                'deadline'         => $now->copy()->addDays(25),
                'address'          => 'San Isidro, Lima',
                'department'       => 'Lima',
                'province'         => 'Lima',
                'country'          => 'Perú',
                'company_id'       => $companyIds[0],
                'location_id'      => 3, // Híbrido
                'state_id'         => $activeStateId,
                'category_id'      => 1, // Informatica
                'work_schedule_id' => 1, // Jornada Completa
                'contract_type_id' => 2, // Plazo fijo
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'title'            => 'DevOps Engineer',
                'slug'             => 'devops-engineer-' . time() . '3',
                'description'      => "Buscamos un DevOps Engineer apasionado por la automatización y la infraestructura cloud. Gestionarás los pipelines de CI/CD y garantizarás la disponibilidad de nuestros servicios.",
                'requirements'     => "- Experiencia con AWS o Azure\n- Conocimiento de Docker y Kubernetes\n- Scripting en Bash o Python\n- Experiencia con Terraform",
                'benefits'         => "- 100% Remoto\n- Seguro médico premium\n- Bono anual por desempeño\n- Días libres adicionales",
                'salary'           => 5500.00,
                'salary_currency'  => 'SOLES',
                'publication_date' => $now->copy()->subDays(3),
                'deadline'         => null,
                'address'          => 'Remoto',
                'department'       => 'Lima',
                'province'         => 'Lima',
                'country'          => 'Perú',
                'company_id'       => $companyIds[0],
                'location_id'      => 1, // Remoto
                'state_id'         => $activeStateId,
                'category_id'      => 4, // Tecnologia
                'work_schedule_id' => 1, // Jornada Completa
                'contract_type_id' => 1, // Indeterminado
                'created_at'       => $now,
                'updated_at'       => $now,
            ],

            // Grupo Andino Comercial
            [
                'title'            => 'Asistente Administrativo',
                'slug'             => 'asistente-administrativo-' . time() . '4',
                'description'      => "Grupo Andino busca un Asistente Administrativo para apoyar las operaciones de nuestra área comercial en Lima.\n\nGestionarás documentación, coordinación de reuniones y soporte a los equipos de ventas.",
                'requirements'     => "- Titulado en Administración o carrera afín\n- Manejo de Office (Excel avanzado)\n- Experiencia mínima de 1 año en posiciones similares\n- Buena comunicación oral y escrita",
                'benefits'         => "- Sueldo competitivo\n- Seguro médico\n- Descuentos en productos del grupo\n- Línea de carrera",
                'salary'           => 1800.00,
                'salary_currency'  => 'SOLES',
                'publication_date' => $now->copy()->subDays(4),
                'deadline'         => $now->copy()->addDays(20),
                'address'          => 'Av. La Marina 2000, San Miguel',
                'department'       => 'Lima',
                'province'         => 'Lima',
                'country'          => 'Perú',
                'company_id'       => $companyIds[1],
                'location_id'      => 2, // Presencial
                'state_id'         => $activeStateId,
                'category_id'      => 3, // Administrativo
                'work_schedule_id' => 1, // Jornada Completa
                'contract_type_id' => 2, // Plazo fijo
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'title'            => 'Supervisor de Logística',
                'slug'             => 'supervisor-logistica-' . time() . '5',
                'description'      => "Buscamos un Supervisor de Logística para gestionar el transporte, almacenamiento y distribución de productos a nivel nacional.\n\nLiderarás un equipo de 10 personas y coordinarás con proveedores externos.",
                'requirements'     => "- Licenciado en Logística, Ingeniería Industrial o afines\n- Mínimo 3 años de experiencia en logística\n- Conocimiento de SAP\n- Licencia de conducir B2C",
                'benefits'         => "- Vehículo asignado\n- Gastos de representación\n- Seguro médico familiar\n- Bono trimestral",
                'salary'           => 3200.00,
                'salary_currency'  => 'SOLES',
                'publication_date' => $now->copy()->subDays(5),
                'deadline'         => $now->copy()->addDays(15),
                'address'          => 'Callao Industrial',
                'department'       => 'Lima',
                'province'         => 'Callao',
                'country'          => 'Perú',
                'company_id'       => $companyIds[1],
                'location_id'      => 2, // Presencial
                'state_id'         => $activeStateId,
                'category_id'      => 3, // Administrativo
                'work_schedule_id' => 1, // Jornada Completa
                'contract_type_id' => 1, // Indeterminado
                'created_at'       => $now,
                'updated_at'       => $now,
            ],

            // Innova Marketing Digital
            [
                'title'            => 'Especialista en Marketing Digital',
                'slug'             => 'especialista-marketing-digital-' . time() . '6',
                'description'      => "Únete a Innova como Especialista en Marketing Digital. Diseñarás y ejecutarás campañas en redes sociales, Google Ads y email marketing para clientes de distintas industrias.",
                'requirements'     => "- 2+ años en marketing digital\n- Certificación Google Ads o Meta Blueprint\n- Experiencia con herramientas de análisis (GA4, Semrush)\n- Inglés intermedio",
                'benefits'         => "- Trabajo híbrido 3/2\n- Capacitaciones internacionales\n- Membresía a plataformas premium\n- Ambiente creativo y joven",
                'salary'           => 2800.00,
                'salary_currency'  => 'SOLES',
                'publication_date' => $now->copy()->subDays(2),
                'deadline'         => $now->copy()->addDays(21),
                'address'          => 'Calle Las Camelias 790',
                'department'       => 'Lima',
                'province'         => 'Lima',
                'country'          => 'Perú',
                'company_id'       => $companyIds[2],
                'location_id'      => 3, // Híbrido
                'state_id'         => $activeStateId,
                'category_id'      => 2, // Marketing
                'work_schedule_id' => 1, // Jornada Completa
                'contract_type_id' => 2, // Plazo fijo
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'title'            => 'Content Creator & Community Manager',
                'slug'             => 'content-creator-community-manager-' . time() . '7',
                'description'      => "Buscamos un talentoso creador de contenido para gestionar las redes sociales de nuestros clientes. Producirás videos, reels, infografías y posts de alto impacto.",
                'requirements'     => "- Portafolio de contenido digital\n- Dominio de Canva, Adobe Premiere o CapCut\n- Conocimiento de tendencias en redes sociales\n- Redacción creativa y ortografía impecable",
                'benefits'         => "- 100% Remoto\n- Horario flexible\n- Acceso a herramientas premium\n- Comisiones por resultados",
                'salary'           => 2000.00,
                'salary_currency'  => 'SOLES',
                'publication_date' => $now->copy()->subDays(1),
                'deadline'         => $now->copy()->addDays(28),
                'address'          => 'Remoto',
                'department'       => 'Lima',
                'province'         => 'Lima',
                'country'          => 'Perú',
                'company_id'       => $companyIds[2],
                'location_id'      => 1, // Remoto
                'state_id'         => $activeStateId,
                'category_id'      => 2, // Marketing
                'work_schedule_id' => 3, // Jornada Parcial
                'contract_type_id' => 3, // Por temporada
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'title'            => 'Practicante de Marketing',
                'slug'             => 'practicante-marketing-' . time() . '8',
                'description'      => "Oportunidad ideal para estudiantes o egresados de Marketing. Aprenderás de la mano de profesionales del sector digital trabajando en proyectos reales con clientes nacionales e internacionales.",
                'requirements'     => "- Estudiante de 8vo ciclo en adelante o recién egresado\n- Conocimientos básicos de redes sociales\n- Actitud proactiva y ganas de aprender",
                'benefits'         => "- Beca de S/ 1,200 mensual\n- Horario de prácticas flexible\n- Constancia al finalizar\n- Posibilidad de contrato",
                'salary'           => 1200.00,
                'salary_currency'  => 'SOLES',
                'publication_date' => $now->copy()->subDays(3),
                'deadline'         => $now->copy()->addDays(14),
                'address'          => 'San Isidro, Lima',
                'department'       => 'Lima',
                'province'         => 'Lima',
                'country'          => 'Perú',
                'company_id'       => $companyIds[2],
                'location_id'      => 3, // Híbrido
                'state_id'         => $activeStateId,
                'category_id'      => 2, // Marketing
                'work_schedule_id' => 2, // Becas/Prácticas
                'contract_type_id' => 3, // Por temporada
                'created_at'       => $now,
                'updated_at'       => $now,
            ],

            // FinanzasPro Consulting
            [
                'title'            => 'Analista Contable Senior',
                'slug'             => 'analista-contable-senior-' . time() . '9',
                'description'      => "FinanzasPro busca un Analista Contable con experiencia en cierre de estados financieros, declaraciones tributarias y manejo de libros contables electrónicos SUNAT.",
                'requirements'     => "- CPC colegiado y habilitado\n- Experiencia mínima de 4 años\n- Dominio de COA, declaraciones PDT\n- Conocimiento de NIIF",
                'benefits'         => "- Sueldo competitivo según experiencia\n- Seguro médico\n- Capacitaciones tributarias\n- Horario L-V 8am-6pm",
                'salary'           => 3500.00,
                'salary_currency'  => 'SOLES',
                'publication_date' => $now->copy()->subDays(6),
                'deadline'         => $now->copy()->addDays(18),
                'address'          => 'Jr. Lampa 801, Cercado de Lima',
                'department'       => 'Lima',
                'province'         => 'Lima',
                'country'          => 'Perú',
                'company_id'       => $companyIds[3],
                'location_id'      => 2, // Presencial
                'state_id'         => $activeStateId,
                'category_id'      => 3, // Administrativo
                'work_schedule_id' => 1, // Jornada Completa
                'contract_type_id' => 1, // Indeterminado
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'title'            => 'Consultor Financiero Junior',
                'slug'             => 'consultor-financiero-junior-' . time() . '10',
                'description'      => "Oportunidad para jóvenes profesionales en finanzas que busquen desarrollar carrera en consultoría. Trabajarás en proyectos de valorización de empresas, modelado financiero y due diligence.",
                'requirements'     => "- Egresado de Economía, Finanzas o afines\n- Dominio de Excel (tablas dinámicas, Power Query)\n- Inglés B2 o superior\n- Disponibilidad inmediata",
                'benefits'         => "- Plan de carrera estructurado\n- Mentoría directa con socios\n- Gastos de movilidad\n- Bono por proyecto",
                'salary'           => 2400.00,
                'salary_currency'  => 'SOLES',
                'publication_date' => $now->copy()->subDays(4),
                'deadline'         => $now->copy()->addDays(22),
                'address'          => 'Jr. Lampa 801',
                'department'       => 'Lima',
                'province'         => 'Lima',
                'country'          => 'Perú',
                'company_id'       => $companyIds[3],
                'location_id'      => 3, // Híbrido
                'state_id'         => $activeStateId,
                'category_id'      => 3, // Administrativo
                'work_schedule_id' => 1, // Jornada Completa
                'contract_type_id' => 2, // Plazo fijo
                'created_at'       => $now,
                'updated_at'       => $now,
            ],

            // Salud Total Clínicas
            [
                'title'            => 'Técnico en Enfermería',
                'slug'             => 'tecnico-enfermeria-' . time() . '11',
                'description'      => "Salud Total Clínicas requiere Técnicos en Enfermería para nuestra red de centros en Lima y provincias. Brindarás atención de calidad a nuestros pacientes en diferentes áreas de hospitalización.",
                'requirements'     => "- Título de Técnico en Enfermería\n- RNE vigente\n- Experiencia mínima de 1 año en hospitales o clínicas\n- Disponibilidad para turnos rotativos",
                'benefits'         => "- Seguro SCTR\n- Uniforme incluido\n- Alimentación\n- Capacitaciones constantes",
                'salary'           => 1700.00,
                'salary_currency'  => 'SOLES',
                'publication_date' => $now->copy()->subDays(7),
                'deadline'         => $now->copy()->addDays(10),
                'address'          => 'Av. Salaverry 1801, Jesús María',
                'department'       => 'Lima',
                'province'         => 'Lima',
                'country'          => 'Perú',
                'company_id'       => $companyIds[4],
                'location_id'      => 2, // Presencial
                'state_id'         => $activeStateId,
                'category_id'      => 3, // Administrativo
                'work_schedule_id' => 4, // Por Horas
                'contract_type_id' => 3, // Por temporada
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'title'            => 'Desarrollador de Software Médico',
                'slug'             => 'desarrollador-software-medico-' . time() . '12',
                'description'      => "Buscamos un desarrollador para mantener y ampliar nuestro sistema de gestión hospitalaria (HIS). Integrarás módulos de laboratorio, farmacia y telemedicina.",
                'requirements'     => "- Experiencia en sistemas de salud o HIS\n- PHP/Laravel o .NET\n- Conocimiento de HL7 o FHIR (deseable)\n- Base de datos MySQL o SQL Server",
                'benefits'         => "- Sueldo acorde al mercado\n- Seguro médico familiar\n- Horario L-V\n- Acceso a plataformas médicas",
                'salary'           => 4000.00,
                'salary_currency'  => 'SOLES',
                'publication_date' => $now->copy()->subDays(2),
                'deadline'         => $now->copy()->addDays(30),
                'address'          => 'Jesús María, Lima',
                'department'       => 'Lima',
                'province'         => 'Lima',
                'country'          => 'Perú',
                'company_id'       => $companyIds[4],
                'location_id'      => 3, // Híbrido
                'state_id'         => $activeStateId,
                'category_id'      => 1, // Informatica
                'work_schedule_id' => 1, // Jornada Completa
                'contract_type_id' => 1, // Indeterminado
                'created_at'       => $now,
                'updated_at'       => $now,
            ],

            // alicorp sac (empresa existente, id 592)
            [
                'title'            => 'Analista de Datos e Inteligencia Comercial',
                'slug'             => 'analista-datos-inteligencia-comercial-' . time() . '13',
                'description'      => "Únete al equipo de Analytics de Alicorp. Transformarás grandes volúmenes de datos en insights accionables que impulsen decisiones estratégicas del negocio.",
                'requirements'     => "- Egresado de Estadística, Ingeniería Industrial o afines\n- Experiencia con SQL y Python/R\n- Tableau o Power BI avanzado\n- Pensamiento analítico y storytelling con datos",
                'benefits'         => "- Sueldo competitivo\n- Productos Alicorp\n- EPS premium\n- Bono por desempeño anual",
                'salary'           => 4200.00,
                'salary_currency'  => 'SOLES',
                'publication_date' => $now->copy()->subDays(1),
                'deadline'         => $now->copy()->addDays(25),
                'address'          => 'La Victoria, Lima',
                'department'       => 'Lima',
                'province'         => 'Lima',
                'country'          => 'Perú',
                'company_id'       => 592,
                'location_id'      => 3, // Híbrido
                'state_id'         => $activeStateId,
                'category_id'      => 4, // Tecnologia
                'work_schedule_id' => 1, // Jornada Completa
                'contract_type_id' => 1, // Indeterminado
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'title'            => 'Jefe de Planta de Producción',
                'slug'             => 'jefe-planta-produccion-' . time() . '14',
                'description'      => "Alicorp busca un profesional con experiencia en gestión de plantas industriales para liderar las operaciones de nuestra planta en Lima. Supervisarás producción, calidad y seguridad.",
                'requirements'     => "- Ing. Alimentaria, Industrial o afines\n- Mínimo 5 años en industria de consumo masivo\n- Conocimiento de BPM, HACCP, ISO\n- Liderazgo de equipos grandes",
                'benefits'         => "- Paquete de compensación total\n- EPS para toda la familia\n- Fondo de pensiones\n- Participación en utilidades",
                'salary'           => 7500.00,
                'salary_currency'  => 'SOLES',
                'publication_date' => $now->copy()->subDays(5),
                'deadline'         => $now->copy()->addDays(15),
                'address'          => 'Av. Argentina 4793, Callao',
                'department'       => 'Lima',
                'province'         => 'Callao',
                'country'          => 'Perú',
                'company_id'       => 592,
                'location_id'      => 2, // Presencial
                'state_id'         => $activeStateId,
                'category_id'      => 3, // Administrativo
                'work_schedule_id' => 1, // Jornada Completa
                'contract_type_id' => 1, // Indeterminado
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'title'            => 'Practicante de Tecnología de Información',
                'slug'             => 'practicante-tecnologia-informacion-' . time() . '15',
                'description'      => "Programa de prácticas en el área de TI de Alicorp. Participarás en proyectos de transformación digital, soporte a infraestructura y desarrollo de aplicaciones internas.",
                'requirements'     => "- Estudiante de Ing. de Sistemas, Informática o afines\n- A partir del 8vo ciclo\n- Conocimientos en redes o programación\n- Disponibilidad para prácticas preprofesionales",
                'benefits'         => "- Asignación económica de S/ 1,500\n- Seguro de accidentes\n- Línea de carrera al terminar\n- Descuentos en productos Alicorp",
                'salary'           => 1500.00,
                'salary_currency'  => 'SOLES',
                'publication_date' => $now->copy()->subDays(0),
                'deadline'         => $now->copy()->addDays(12),
                'address'          => 'La Victoria, Lima',
                'department'       => 'Lima',
                'province'         => 'Lima',
                'country'          => 'Perú',
                'company_id'       => 592,
                'location_id'      => 3, // Híbrido
                'state_id'         => $activeStateId,
                'category_id'      => 1, // Informatica
                'work_schedule_id' => 2, // Becas/Prácticas
                'contract_type_id' => 3, // Por temporada
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
        ];

        // ── 3. Insertar ofertas y sus estados ──────────────────────────────
        foreach ($offers as $offer) {
            $offerId = DB::table('job_opportunity_offer')->insertGetId($offer);
            DB::table('job_opportunity_offer_state_detail')->insert([
                'offer_id'   => $offerId,
                'state_id'   => $activeStateId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $this->command->info('✅ Demo data insertado: 5 empresas + 15 ofertas activas.');
    }
}
