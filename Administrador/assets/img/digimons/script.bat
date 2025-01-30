@echo off
rem Verifica si las imágenes base.png, defeat.png y victory.png existen
if not exist "base.png" (
    echo base.png no encontrado.
    exit /b
)
if not exist "defeat.png" (
    echo defeat.png no encontrado.
    exit /b
)
if not exist "victory.png" (
    echo victory.png no encontrado.
    exit /b
)

rem Lista de nombres de Digimons
set digimons=Koromon Agumon Greymon MetalGreymon Gabumon Garurumon WereGarurumon Patamon Angemon MagnaAngemon Tsukaimon Devimon Myotismon VenomMyotismon Impmon Beelzebumon Belphemon Lucemon Dracmon Baomon Tanemon Palmon Togemon Lillymon Hagurumon Lalamon Lopmon Turuiemon Bokomon Rosemon Elecmon Leomon GrapLeomon SaberLeomon Nyaromon Diaboromon Tentomon Kabuterimon MegaKabuterimon Wargreymon Falcomon Hawkmon Shurimon ShiningGreymon ElementalMon AguaMon FuegoMon MangoMon MonMonMon RaMon CoMon TierraMon HieloMon TestMon CopiaMon

rem Itera sobre cada nombre de Digimon y crea una carpeta para cada uno
for %%D in (%digimons%) do (
    rem Crea la carpeta con el nombre del Digimon
    mkdir "%%D"
    
    rem Copia las imágenes a la carpeta
    copy "base.png" "%%D\"
    copy "defeat.png" "%%D\"
    copy "victory.png" "%%D\"
    
    echo Imagenes copiadas para %%D
)

echo Proceso completo
pause
