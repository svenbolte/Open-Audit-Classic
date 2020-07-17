Set objFirewall = CreateObject("HNetCfg.FwMgr")

Set objPolicy = objFirewall.LocalPolicy.CurrentProfile

Set objAdminSettings = objPolicy.RemoteAdminSettings

objAdminSettings.Enabled = TRUE
