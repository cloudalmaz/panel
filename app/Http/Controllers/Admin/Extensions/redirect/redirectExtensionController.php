<?php

namespace Pterodactyl\Http\Controllers\Admin\Extensions\redirect;

use Illuminate\View\View;
use Illuminate\View\Factory as ViewFactory;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Services\Helpers\BlueprintExtensionLibrary;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Http\RedirectResponse;
use Pterodactyl\Contracts\Repository\SettingsRepositoryInterface;
use Pterodactyl\Http\Requests\Admin\AdminFormRequest;

class redirectSettingsFormRequest extends AdminFormRequest
{
    /**
     * Return all the rules to apply to this request's data.
     */
    public function rules(): array
    {
        return [
            'rname' => 'string|not_regex:/\//',
            'rurl' => 'string|url:http,https',
            'rdel' => 'string',
        ];
    }

    public function attributes(): array
    {
        return [
            'rname' => 'Redirect Name',
            'rurl' => 'Redirect URL',
            'rdel' => 'Redirect to Remove',
        ];
    }
}

class redirectExtensionController extends Controller
{

  /**
   * redirectExtensionController constructor.
   */
  public function __construct(
    private BlueprintExtensionLibrary $blueprint,
  
    private ViewFactory $view,
    private ConfigRepository $config,
    private SettingsRepositoryInterface $settings,
    ) {
  }

  /**
   * Return the admin index view.
   */
  public function index(): View
  {
    if($this->blueprint->dbGet('redirect', 'rname') == null) {$this->blueprint->dbSet('redirect', 'rname', '');}
    if($this->blueprint->dbGet('redirect', 'rurl') == null) {$this->blueprint->dbSet('redirect', 'rurl', '');}
    if($this->blueprint->dbGet('redirect', 'rdel') == null) {$this->blueprint->dbSet('redirect', 'rdel', '');}

    # ADD REDIRECT
    if($this->blueprint->dbGet('redirect', 'rname') != "" && $this->blueprint->dbGet('redirect', 'rurl') != "") {
      shell_exec("cd /var/www/pterodactyl;bash /var/www/pterodactyl/.blueprint/extensions/redirect/private/scripts/addRedirect.sh ".$this->blueprint->dbGet('redirect', 'rname')." ".$this->blueprint->dbGet('redirect', 'rurl'));
      $this->blueprint->dbSet('redirect', 'rname', '');
      $this->blueprint->dbSet('redirect', 'rurl', '');
    };

    # REMOVE REDIRECT
    if($this->blueprint->dbGet('redirect', 'rdel') != "") {
      shell_exec("cd /var/www/pterodactyl;bash /var/www/pterodactyl/.blueprint/extensions/redirect/private/scripts/removeRedirect.sh ".$this->blueprint->dbGet('redirect', 'rdel'));
      $this->blueprint->dbSet('redirect', 'rdel', '');
    };

    # LIST REDIRECTS
    $redirs=shell_exec("cd /var/www/pterodactyl;bash /var/www/pterodactyl/.blueprint/extensions/redirect/private/scripts/listRedirect.sh");
    if($redirs == "") {
      $redirs="You don't have any redirects, time to make one!";
    };

    return $this->view->make(
      'admin.extensions.redirect.index', [
        'blueprint' => $this->blueprint,
        'redirects' => $redirs,
        'root' => "/admin/extensions/blueprint",
      ]
    );
  }

  /**
   * @throws \Pterodactyl\Exceptions\Model\DataValidationException
   * @throws \Pterodactyl\Exceptions\Repository\RecordNotFoundException
   */
  public function update(redirectSettingsFormRequest $request): RedirectResponse
  {
    foreach ($request->normalize() as $key => $value) {
      $this->settings->set('redirect::' . $key, $value);
    }

    return redirect()->route('admin.extensions.redirect.index');
  }
}
