#!/bin/bash
# © Ga1maz (a.cloa.su)
  FOLDER=$(realpath "$(dirname "$0")")

  OWNERSHIP="www-data:www-data" #;

  WEBUSER="www-data" #;
  USERSHELL="/bin/bash" #;

  VERSION="powered by Ga1maz v1.1"

  REPOSITORY="BlueprintFramework/framework"

export BLUEPRINT__FOLDER=$FOLDER
export BLUEPRINT__VERSION=$VERSION
export BLUEPRINT__DEBUG="$FOLDER"/.blueprint/extensions/blueprint/private/debug/logs.txt
export NODE_OPTIONS=--openssl-legacy-provider

if [[ "${BASH_SOURCE[0]}" != "${0}" ]]; then
  _blueprint_completions() {
    local cur cmd opts
    COMPREPLY=()
    cur="${COMP_WORDS[COMP_CWORD]}"
    cmd="${COMP_WORDS[1]}"

    case "${cmd}" in
      -install|-add|-i) opts="$(find "$BLUEPRINT__SOURCEFOLDER"/*.blueprint | sed -e "s|^$BLUEPRINT__SOURCEFOLDER/||g" -e "s|.blueprint$||g")" ;;
      -remove|-r) opts="$(sed "s|,||g" "$BLUEPRINT__SOURCEFOLDER/.blueprint/extensions/blueprint/private/db/installed_extensions")" ;;
      -export) opts="expose" ;;
      -upgrade) opts="remote" ;;
      
      *) opts="-install -add -remove -init -build -export -wipe -version -help -info -debug -upgrade -rerun-install" ;;
    esac

    if [[ ${cur} == * ]]; then
      # shellcheck disable=SC2207
      COMPREPLY=( $(compgen -W "${opts}" -- "${cur}") )
      return 0
    fi
  }
  complete -F _blueprint_completions blueprint
  return 0
fi

if [[ -f "/.dockerenv" ]]; then
  DOCKER="y"
  FOLDER="/app"
else
  DOCKER="n"
fi

if [[ -d "$FOLDER/blueprint" ]]; then mv "$FOLDER/blueprint" "$FOLDER/.blueprint"; fi

if [[ $VERSION != "" ]]; then
  if [[ ! -f "$FOLDER/.blueprint/extensions/blueprint/private/db/version" ]]; then
    sed -E -i "s*::v*$VERSION*g" "$FOLDER/app/BlueprintFramework/Services/PlaceholderService/BlueprintPlaceholderService.php"
    sed -E -i "s*::v*$VERSION*g" "$FOLDER/.blueprint/extensions/blueprint/public/index.html"
    touch "$FOLDER/.blueprint/extensions/blueprint/private/db/version"
  fi
fi

__BuildDir=".blueprint/extensions/blueprint/private/build"

cd "$FOLDER" || return

source scripts/libraries/parse_yaml.sh    || missinglibs+="[parse_yaml]"
source scripts/libraries/grabenv.sh       || missinglibs+="[grabenv]"
source scripts/libraries/logFormat.sh     || missinglibs+="[logFormat]"
source scripts/libraries/misc.sh          || missinglibs+="[misc]"
source scripts/libraries/configutility.sh || missinglibs+="[configutility]"

if [[ "$1" == "-config" ]]; then ConfigUtility; fi

cdhalt() { PRINT FATAL "Attempted navigation into nonexistent directory, halting process."; exit 1; }
depend() {
  # Check for compatible node versions
  nodeVer=$(node -v)
  if [[ $nodeVer != "v17."* ]] \
  && [[ $nodeVer != "v18."* ]] \
  && [[ $nodeVer != "v19."* ]] \
  && [[ $nodeVer != "v20."* ]] \
  && [[ $nodeVer != "v21."* ]] \
  && [[ $nodeVer != "v22."* ]]; then
    DEPEND_MISSING=true
  fi

  if \
  ! [ -x "$(command -v unzip)" ] ||                          # unzip
  ! [ -x "$(command -v node)" ] ||                           # node
  ! [ -x "$(command -v yarn)" ] ||                           # yarn
  ! [ -x "$(command -v zip)" ] ||                            # zip
  ! [ -x "$(command -v curl)" ] ||                           # curl
  ! [ -x "$(command -v php)" ] ||                            # php
  ! [ -x "$(command -v git)" ] ||                            # git
  ! [ -x "$(command -v grep)" ] ||                           # grep
  ! [ -x "$(command -v sed)" ] ||                            # sed
  ! [ -x "$(command -v awk)" ] ||                            # awk
  ! [ -x "$(command -v tput)" ] ||                           # tput
  ! [ "$(ls "node_modules/"*"cross-env"* 2> /dev/null)" ] || # cross-env
  ! [ "$(ls "node_modules/"*"webpack"* 2> /dev/null)"   ] || # webpack
  ! [ "$(ls "node_modules/"*"react"* 2> /dev/null)"     ] || # react
  [[ $missinglibs != "" ]]; then                             # internal
    DEPEND_MISSING=true
  fi

  if [[ $DEPEND_MISSING == true ]]; then
    PRINT FATAL "Some framework dependencies are not installed or detected."

    if [[ $nodeVer != "v18."* ]] \
    && [[ $nodeVer != "v19."* ]] \
    && [[ $nodeVer != "v20."* ]] \
    && [[ $nodeVer != "v21."* ]] \
    && [[ $nodeVer != "v22."* ]]; then
      PRINT FATAL "Required dependency \"node\" is using an unsupported version."
    fi

    if ! [ -x "$(command -v unzip)"                          ]; then PRINT FATAL "Required dependency \"unzip\" is not installed or detected.";     fi
    if ! [ -x "$(command -v node)"                           ]; then PRINT FATAL "Required dependency \"node\" is not installed or detected.";      fi
    if ! [ -x "$(command -v yarn)"                           ]; then PRINT FATAL "Required dependency \"yarn\" is not installed or detected.";      fi
    if ! [ -x "$(command -v zip)"                            ]; then PRINT FATAL "Required dependency \"zip\" is not installed or detected.";       fi
    if ! [ -x "$(command -v curl)"                           ]; then PRINT FATAL "Required dependency \"curl\" is not installed or detected.";      fi
    if ! [ -x "$(command -v php)"                            ]; then PRINT FATAL "Required dependency \"php\" is not installed or detected.";       fi
    if ! [ -x "$(command -v git)"                            ]; then PRINT FATAL "Required dependency \"git\" is not installed or detected.";       fi
    if ! [ -x "$(command -v grep)"                           ]; then PRINT FATAL "Required dependency \"grep\" is not installed or detected.";      fi
    if ! [ -x "$(command -v sed)"                            ]; then PRINT FATAL "Required dependency \"sed\" is not installed or detected.";       fi
    if ! [ -x "$(command -v awk)"                            ]; then PRINT FATAL "Required dependency \"awk\" is not installed or detected.";       fi
    if ! [ -x "$(command -v tput)"                           ]; then PRINT FATAL "Required dependency \"tput\" is not installed or detected.";      fi
    if ! [ "$(ls "node_modules/"*"cross-env"* 2> /dev/null)" ]; then PRINT FATAL "Required dependency \"cross-env\" is not installed or detected."; fi
    if ! [ "$(ls "node_modules/"*"webpack"* 2> /dev/null)"   ]; then PRINT FATAL "Required dependency \"webpack\" is not installed or detected.";   fi
    if ! [ "$(ls "node_modules/"*"react"* 2> /dev/null)"     ]; then PRINT FATAL "Required dependency \"react\" is not installed or detected.";     fi

    if [[ $missinglibs == *"[parse_yaml]"*    ]]; then PRINT FATAL "Required internal dependency \"internal:parse_yaml\" is not installed or detected."; fi
    if [[ $missinglibs == *"[grabEnv]"*       ]]; then PRINT FATAL "Required internal dependency \"internal:grabEnv\" is not installed or detected.";    fi
    if [[ $missinglibs == *"[logFormat]"*     ]]; then PRINT FATAL "Required internal dependency \"internal:logFormat\" is not installed or detected.";  fi
    if [[ $missinglibs == *"[misc]"*          ]]; then PRINT FATAL "Required internal dependency \"internal:misc\" is not installed or detected.";       fi
    if [[ $missinglibs == *"[configutility]"* ]]; then PRINT FATAL "Required internal dependency \"internal:configutility\" is not installed or detected.";       fi

    exit 1
  fi
}

assignflags() {
  F_ignorePlaceholders=false
  F_forceLegacyPlaceholders=false
  F_hasInstallScript=false
  F_hasRemovalScript=false
  F_hasExportScript=false
  F_developerIgnoreInstallScript=false
  F_developerIgnoreRebuild=false
  F_developerForceMigrate=false
  F_developerKeepApplicationCache=false
  F_developerEscalateInstallScript=false
  F_developerEscalateExportScript=false
  if [[ ( $flags == *"ignorePlaceholders,"*             ) || ( $flags == *"ignorePlaceholders"             ) ]]; then F_ignorePlaceholders=true             ;fi
  if [[ ( $flags == *"forceLegacyPlaceholders,"*        ) || ( $flags == *"forceLegacyPlaceholders"        ) ]]; then F_forceLegacyPlaceholders=true        ;fi
  if [[ ( $flags == *"hasInstallScript,"*               ) || ( $flags == *"hasInstallScript"               ) ]]; then F_hasInstallScript=true               ;fi
  if [[ ( $flags == *"hasRemovalScript,"*               ) || ( $flags == *"hasRemovalScript"               ) ]]; then F_hasRemovalScript=true               ;fi
  if [[ ( $flags == *"hasExportScript,"*                ) || ( $flags == *"hasExportScript"                ) ]]; then F_hasExportScript=true                ;fi
  if [[ ( $flags == *"developerIgnoreInstallScript,"*   ) || ( $flags == *"developerIgnoreInstallScript"   ) ]]; then F_developerIgnoreInstallScript=true   ;fi
  if [[ ( $flags == *"developerIgnoreRebuild,"*         ) || ( $flags == *"developerIgnoreRebuild"         ) ]]; then F_developerIgnoreRebuild=true         ;fi
  if [[ ( $flags == *"developerForceMigrate,"*          ) || ( $flags == *"developerForceMigrate"          ) ]]; then F_developerForceMigrate=true          ;fi
  if [[ ( $flags == *"developerKeepApplicationCache,"*  ) || ( $flags == *"developerKeepApplicationCache"  ) ]]; then F_developerKeepApplicationCache=true  ;fi
  if [[ ( $flags == *"developerEscalateInstallScript,"* ) || ( $flags == *"developerEscalateInstallScript" ) ]]; then F_developerEscalateInstallScript=true ;fi
  if [[ ( $flags == *"developerEscalateExportScript,"*  ) || ( $flags == *"developerEscalateExportScript"  ) ]]; then F_developerEscalateExportScript=true  ;fi
}

placeshortcut() {
  PRINT INFO "Placing Blueprint command shortcut.."
  {
    touch /usr/local/bin/blueprint
    chmod u+x \
      "$FOLDER/blueprint.sh" \
      /usr/local/bin/blueprint
  } >> "$BLUEPRINT__DEBUG"
  echo -e \
    "#!/bin/bash \n" \
    "if [[ \"\${BASH_SOURCE[0]}\" != \"\${0}\" ]]; then export BLUEPRINT__SOURCEFOLDER=\"$FOLDER\"; source \"$FOLDER/blueprint.sh\"; return 0; fi; "\
    "bash $FOLDER/blueprint.sh -bash \$@;" \
    > /usr/local/bin/blueprint
}
if ! [ -x "$(command -v blueprint)" ]; then placeshortcut; fi


if [[ $1 != "-bash" ]]; then
  if dbValidate "blueprint.setupFinished"; then
    PRINT FATAL "Installation process has already been finished before, consider using the 'blueprint' command."
    exit 2
  else
    if [[ $1 != "--post-upgrade" ]]; then
      C0="\x1b[0m"
      C1="\x1b[31;43;1m"
      C2="\x1b[32;44;1m"
      C3="\x1b[34;45;1m"
      C3="\x1b[0;37;1m"
      echo -e "$C0" \
        "\n$C4  ██$C1▌$C2▌$C3▌$C0   Blueprint Framework by" \
        "\n$C4██  ██$C1▌$C2▌$C3▌$C0 https://a.cloa.su" \
        "\n$C4  ████$C1▌$C2▌$C3▌$C0 Almaz Ganiev (a.cloa.su / Ga1maz)\n";
    fi

    PRINT INFO "Searching and validating framework dependencies.."
    
    placeshortcut 

    PRINT INFO "Linking directories and filesystems.."
    {
      ln -s -r -T "$FOLDER/.blueprint/extensions/blueprint/public" "$FOLDER/public/extensions/blueprint"
      ln -s -r -T "$FOLDER/.blueprint/extensions/blueprint/assets" "$FOLDER/public/assets/extensions/blueprint"
      ln -s -r -T "$FOLDER/scripts/libraries" "$FOLDER/.blueprint/lib"
    } 2>> "$BLUEPRINT__DEBUG"
    php artisan storage:link &>> "$BLUEPRINT__DEBUG"

    cp "$FOLDER/.blueprint/assets/Emblem/emblem.jpg" "$FOLDER/.blueprint/extensions/blueprint/assets/logo.jpg"

    PRINT INPUT "Would you like to put your application into maintenance while Blueprint is installing? (Y/n)"
    read -r YN
    if [[ ( $YN == "y"* ) || ( $YN == "Y"* ) || ( $YN == "" ) ]]; then
      MAINTENANCE="true"
      PRINT INFO "Put application into maintenance mode."
      php artisan down &>> "$BLUEPRINT__DEBUG"
    else
      MAINTENANCE="false"
      PRINT INFO "Putting application into maintenance has been skipped."
    fi

    PRINT INFO "Flushing view, config and route cache.."
    {
      php artisan view:cache
      php artisan config:cache
      php artisan route:clear
      php artisan cache:clear
      php artisan bp:cache
    } &>> "$BLUEPRINT__DEBUG"

    if [[ ( $1 != "--post-upgrade" ) && ( $DOCKER != "y" ) ]]; then
      PRINT INPUT "Would you like to migrate your database? (Y/n)"
      read -r YN
      if [[ ( $YN == "y"* ) || ( $YN == "Y"* ) || ( $YN == "" ) ]]; then
        PRINT INFO "Running database migrations.."
        php artisan migrate --force
      else
        PRINT INFO "Database migrations have been skipped."
      fi
    fi

    PRINT INFO "Changing Pterodactyl file ownership to '$OWNERSHIP'.."
    find "$FOLDER/" \
      -path "$FOLDER/node_modules" -prune \
      -o -exec chown "$OWNERSHIP" {} + &>> "$BLUEPRINT__DEBUG"

    PRINT INFO "Rebuilding panel assets.."
    yarn run build:production --progress

    if [[ $DOCKER != "y" ]]; then
      PRINT INFO "Syncing Blueprint-related database values.."
      php artisan bp:sync
    fi

    if [[ $DOCKER != "y" ]] && [[ $MAINTENANCE == "true" ]]; then
      PRINT INFO "Put application into production."
      php artisan up &>> "$BLUEPRINT__DEBUG"
    fi

    if [[ $1 != "--post-upgrade" ]]; then
      PRINT SUCCESS "Blueprint has completed its installation process."
    fi

    dbAdd "blueprint.setupFinished"
    sed -i "s~NOTINSTALLED~INSTALLED~g" "$FOLDER/app/BlueprintFramework/Services/PlaceholderService/BlueprintPlaceholderService.php"
    exit 0
  fi
fi

Command() {
  PRINT FATAL "'$cmd' is not a valid command or argument. Use argument '-help' for a list of commands."
}

cmd="${2}"
case "$cmd" in
  -add|-install|-i) source ./scripts/commands/extensions/install.sh ;;
  -remove|-r) source ./scripts/commands/extensions/remove.sh ;;
  -init|-I) source ./scripts/commands/developer/init.sh ;;
  -build|-b) source ./scripts/commands/developer/build.sh ;;
  -wipe|-w) source ./scripts/commands/developer/wipe.sh ;;
  -export|-e) source ./scripts/commands/developer/export.sh ;;
  -info|-f) source ./scripts/commands/misc/info.sh ;;
  -debug) source ./scripts/commands/misc/debug.sh ;;
  -help|-h|help|'') source ./scripts/commands/misc/help.sh ;;
  -version|-v) source ./scripts/commands/misc/version.sh ;;
  -rerun-install) source ./scripts/commands/advanced/rerun-install.sh ;;
  -upgrade) source ./scripts/commands/advanced/upgrade.sh ;;
esac

shift 2
Command "$*"
exit 0
