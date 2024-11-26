import React from 'react';

/* blueprint/import *//* VersionchangerImportStart */import VersionchangerChjsqifzpw from '@/blueprint/extensions/versionchanger/VersionChangerContainer';/* VersionchangerImportEnd *//* MinecraftplayermanagerImportStart */import MinecraftplayermanagerVrlfmxviuz from '@/blueprint/extensions/minecraftplayermanager/PlayerManagerContainer';/* MinecraftplayermanagerImportEnd */

interface RouteDefinition {
  path: string;
  name: string | undefined;
  component: React.ComponentType;
  exact?: boolean;
  adminOnly: boolean | false;
  identifier: string;
}
interface ServerRouteDefinition extends RouteDefinition {
  permission: string | string[] | null;
}
interface Routes {
  account: RouteDefinition[];
  server: ServerRouteDefinition[];
}

export default {
  account: [
    /* routes/account *//* VersionchangerAccountRouteStart *//* VersionchangerAccountRouteEnd *//* MinecraftplayermanagerAccountRouteStart *//* MinecraftplayermanagerAccountRouteEnd */
  ],
  server: [
    /* routes/server *//* VersionchangerServerRouteStart */{ path: '/minecraft/versions', permission: 'file.update', name: 'Версии', component: VersionchangerChjsqifzpw, adminOnly: false, identifier: 'versionchanger' },/* VersionchangerServerRouteEnd *//* MinecraftplayermanagerServerRouteStart */{ path: '/minecraft/players', permission: 'control.console', name: 'Игроки', component: MinecraftplayermanagerVrlfmxviuz, adminOnly: false, identifier: 'minecraftplayermanager' },/* MinecraftplayermanagerServerRouteEnd */
  ],
} as Routes;
